import json
import os
import sys
from pathlib import Path
from urllib.parse import urljoin

from playwright.sync_api import sync_playwright, TimeoutError as PlaywrightTimeoutError

BASE_URL = os.environ.get("BASE_URL", "http://localhost:8080").rstrip("/") + "/"
ARTIFACT_DIR = Path(os.environ.get("RESPONSIVE_ARTIFACT_DIR", "responsive-audit-artifacts"))
ARTIFACT_DIR.mkdir(parents=True, exist_ok=True)

VIEWPORTS = {
    "mobile": {"width": 390, "height": 844},
    "tablet": {"width": 768, "height": 1024},
    "laptop": {"width": 1366, "height": 768},
    "full-hd": {"width": 1920, "height": 1080},
}

PUBLIC_ROUTES = ["/", "/rooms", "/restaurant", "/facilities", "/contact", "/login", "/register"]

ROLE_CASES = {
    "guest": {
        "email_env": "AUDIT_GUEST_EMAIL",
        "password_env": "AUDIT_GUEST_PASSWORD",
        "expected_prefix": "/guest",
        "routes": [
            "/guest/dashboard", "/guest/my-bookings", "/guest/my-stay",
            "/guest/room-service", "/guest/restaurant-orders", "/guest/facilities-booking",
        ],
    },
    "receptionist": {
        "email_env": "AUDIT_RECEPTIONIST_EMAIL",
        "password_env": "AUDIT_RECEPTIONIST_PASSWORD",
        "expected_prefix": "/receptionist",
        "routes": [
            "/receptionist/dashboard", "/receptionist/check-in", "/receptionist/check-out",
            "/receptionist/room-assignment", "/receptionist/folio", "/receptionist/payments",
            "/receptionist/reservations", "/receptionist/guests", "/receptionist/guest-history",
            "/receptionist/room-availability", "/receptionist/house-status",
        ],
    },
    "manager": {
        "email_env": "AUDIT_MANAGER_EMAIL",
        "password_env": "AUDIT_MANAGER_PASSWORD",
        "expected_prefix": "/manager",
        "routes": [
            "/manager/dashboard", "/manager/reservations", "/manager/front-desk", "/manager/folio",
            "/manager/rooms-inventory", "/manager/room-service-orders",
            "/manager/restaurant-gastronomy", "/manager/facilities-wellness",
            "/manager/finance-billing", "/manager/reports", "/manager/users-control",
        ],
    },
    "admin": {
        "email_env": "AUDIT_ADMIN_EMAIL",
        "password_env": "AUDIT_ADMIN_PASSWORD",
        "expected_prefix": "/admin",
        "routes": [
            "/admin/dashboard", "/admin/reservations", "/admin/front-desk", "/admin/folio",
            "/admin/rooms-inventory", "/admin/room-service-orders",
            "/admin/restaurant-gastronomy", "/admin/restaurant-gastronomy?view=menu",
            "/admin/facilities-wellness", "/admin/finance-billing", "/admin/reports",
            "/admin/users-control", "/admin/contact-messages",
        ],
    },
}

REPRESENTATIVE_ROUTES = {
    "/",
    "/login",
    "/guest/dashboard",
    "/receptionist/dashboard",
    "/manager/dashboard",
    "/admin/dashboard",
    "/admin/reports",
    "/admin/restaurant-gastronomy?view=menu",
}


def safe_name(value: str) -> str:
    return "".join(ch if ch.isalnum() else "-" for ch in value).strip("-")[:120]


def capture(page, route: str, viewport_name: str, scope: str, suffix: str = "") -> None:
    filename = f"{viewport_name}-{scope}-{safe_name(route)}{suffix}.png"
    page.screenshot(path=str(ARTIFACT_DIR / filename), full_page=True)


def audit_page(page, route: str, viewport_name: str, scope: str) -> dict:
    url = urljoin(BASE_URL, route.lstrip("/"))
    page_errors: list[str] = []

    def record_page_error(error) -> None:
        page_errors.append(str(error))

    page.on("pageerror", record_page_error)

    try:
        response = page.goto(url, wait_until="domcontentloaded", timeout=45_000)
        page.wait_for_timeout(750)
    except PlaywrightTimeoutError as exc:
        page.remove_listener("pageerror", record_page_error)
        return {"route": route, "ok": False, "error": f"timeout: {exc}"}

    status = response.status if response else None
    overflow = page.evaluate(
        """
        () => {
            const root = document.documentElement;
            const body = document.body;
            const offenders = [...document.querySelectorAll('body *')]
                .filter((element) => {
                    const rect = element.getBoundingClientRect();
                    const style = getComputedStyle(element);
                    if (style.position === 'fixed') return false;
                    return rect.right > window.innerWidth + 2 || rect.left < -2;
                })
                .slice(0, 15)
                .map((element) => ({
                    tag: element.tagName,
                    id: element.id,
                    className: String(element.className || '').slice(0, 180),
                    right: Math.round(element.getBoundingClientRect().right),
                    left: Math.round(element.getBoundingClientRect().left),
                }));

            return {
                documentOverflow: Math.max(root.scrollWidth, body.scrollWidth) > window.innerWidth + 2,
                scrollWidth: Math.max(root.scrollWidth, body.scrollWidth),
                innerWidth: window.innerWidth,
                offenders,
            };
        }
        """
    )

    ok = bool(status and status < 400 and not page_errors and not overflow["documentOverflow"])
    if not ok or route in REPRESENTATIVE_ROUTES:
        capture(page, route, viewport_name, scope, "-failed" if not ok else "")

    page.remove_listener("pageerror", record_page_error)
    return {
        "route": route,
        "status": status,
        "ok": ok,
        "pageErrors": page_errors,
        "overflow": overflow,
    }


def login(page, email: str, password: str, expected_prefix: str) -> tuple[bool, str]:
    page.goto(urljoin(BASE_URL, "login"), wait_until="domcontentloaded", timeout=45_000)
    page.fill('input[name="email"]', email)
    page.fill('input[name="password"]', password)

    captcha = page.locator('.g-recaptcha')
    if captcha.count() > 0:
        return False, "reCAPTCHA is active. Use development test keys or disable it only in the isolated audit environment."

    page.click('button[type="submit"]')
    page.wait_for_timeout(1_200)
    return expected_prefix in page.url, page.url


def main() -> int:
    results: dict = {"baseUrl": BASE_URL, "viewports": {}, "warnings": []}

    with sync_playwright() as playwright:
        browser = playwright.chromium.launch(headless=True)

        for viewport_name, viewport in VIEWPORTS.items():
            viewport_results = {"public": [], "roles": {}}
            public_context = browser.new_context(viewport=viewport)
            public_page = public_context.new_page()
            for route in PUBLIC_ROUTES:
                viewport_results["public"].append(audit_page(public_page, route, viewport_name, "public"))
            public_context.close()

            for role, config in ROLE_CASES.items():
                email = os.environ.get(config["email_env"])
                password = os.environ.get(config["password_env"])
                if not email or not password:
                    results["warnings"].append(
                        f"Skipped {role} at {viewport_name}: set {config['email_env']} and {config['password_env']}."
                    )
                    continue

                context = browser.new_context(viewport=viewport)
                page = context.new_page()
                authenticated, destination = login(page, email, password, config["expected_prefix"])
                if not authenticated:
                    viewport_results["roles"][role] = [{
                        "route": "/login",
                        "ok": False,
                        "error": f"Login did not reach {config['expected_prefix']}; destination={destination}",
                    }]
                    context.close()
                    continue

                viewport_results["roles"][role] = [
                    audit_page(page, route, viewport_name, role) for route in config["routes"]
                ]
                context.close()

            results["viewports"][viewport_name] = viewport_results

        browser.close()

    result_path = ARTIFACT_DIR / "responsive-audit.json"
    result_path.write_text(json.dumps(results, indent=2), encoding="utf-8")

    failures = []
    for viewport_data in results["viewports"].values():
        failures.extend(item for item in viewport_data["public"] if not item.get("ok"))
        for role_items in viewport_data["roles"].values():
            failures.extend(item for item in role_items if not item.get("ok"))

    print(json.dumps({"failureCount": len(failures), "artifact": str(result_path)}, indent=2))
    return 1 if failures else 0


if __name__ == "__main__":
    sys.exit(main())
