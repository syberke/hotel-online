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
    "desktop": {"width": 1440, "height": 900},
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
            "/receptionist/dashboard", "/receptionist/walk-in", "/receptionist/check-in",
            "/receptionist/check-out", "/receptionist/folio", "/receptionist/payments",
            "/receptionist/reservations", "/receptionist/guests", "/receptionist/guest-history",
            "/receptionist/room-availability", "/receptionist/house-status",
        ],
    },
    "manager": {
        "email_env": "AUDIT_MANAGER_EMAIL",
        "password_env": "AUDIT_MANAGER_PASSWORD",
        "expected_prefix": "/manager",
        "routes": [
            "/manager/dashboard", "/manager/reservations", "/manager/front-desk",
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
            "/admin/dashboard", "/admin/reservations", "/admin/front-desk",
            "/admin/rooms-inventory", "/admin/room-service-orders",
            "/admin/restaurant-gastronomy", "/admin/restaurant-gastronomy?view=menu",
            "/admin/facilities-wellness", "/admin/finance-billing", "/admin/reports",
            "/admin/users-control",
        ],
    },
}


def safe_name(value: str) -> str:
    return "".join(ch if ch.isalnum() else "-" for ch in value).strip("-")[:120]


def audit_page(page, route: str, viewport_name: str, scope: str) -> dict:
    url = urljoin(BASE_URL, route.lstrip("/"))
    page_errors: list[str] = []
    page.on("pageerror", lambda error: page_errors.append(str(error)))

    try:
        response = page.goto(url, wait_until="domcontentloaded", timeout=45_000)
        page.wait_for_timeout(750)
    except PlaywrightTimeoutError as exc:
        return {"scope": scope, "viewport": viewport_name, "route": route, "ok": False, "failures": [f"navigation timeout: {exc}"]}

    status = response.status if response else 0
    metrics = page.evaluate(
        """
        () => {
            const html = document.documentElement;
            const body = document.body;
            const scrollWidth = Math.max(html.scrollWidth, body ? body.scrollWidth : 0);
            return {
                width: window.innerWidth,
                scrollWidth,
                overflowX: Math.max(0, scrollWidth - window.innerWidth),
                viewportMeta: document.querySelector('meta[name="viewport"]')?.getAttribute('content') || '',
                title: document.title,
            };
        }
        """
    )

    failures: list[str] = []
    if status >= 400 or status == 0:
        failures.append(f"HTTP {status}")
    if page_errors:
        failures.append("page errors: " + " | ".join(page_errors[:3]))
    if metrics["overflowX"] > 4:
        failures.append(f"horizontal overflow {metrics['overflowX']}px")
    if "width=device-width" not in metrics["viewportMeta"]:
        failures.append("missing responsive viewport meta")

    ok = not failures
    if not ok:
        screenshot = ARTIFACT_DIR / f"{viewport_name}-{scope}-{safe_name(route)}.png"
        page.screenshot(path=str(screenshot), full_page=True)

    return {"scope": scope, "viewport": viewport_name, "route": route, "status": status, "ok": ok, "failures": failures, "metrics": metrics}


def login(page, role: str, case: dict) -> tuple[bool, str]:
    email = os.environ.get(case["email_env"], "")
    password = os.environ.get(case["password_env"], "")
    if not email or not password:
        return False, "missing audit credentials"

    page.goto(urljoin(BASE_URL, "login"), wait_until="domcontentloaded", timeout=45_000)
    page.fill('input[name="email"]', email)
    page.fill('input[name="password"]', password)
    page.locator('button[type="submit"]').click()
    try:
        page.wait_for_load_state("domcontentloaded", timeout=30_000)
        page.wait_for_timeout(500)
    except PlaywrightTimeoutError:
        pass

    current_path = page.evaluate("() => window.location.pathname")
    return current_path.startswith(case["expected_prefix"]), current_path


def main() -> int:
    results: list[dict] = []

    with sync_playwright() as playwright:
        browser = playwright.chromium.launch(headless=True)

        for viewport_name, viewport in VIEWPORTS.items():
            public_context = browser.new_context(viewport=viewport)
            public_page = public_context.new_page()
            for route in PUBLIC_ROUTES:
                results.append(audit_page(public_page, route, viewport_name, "public"))
            public_context.close()

            for role, case in ROLE_CASES.items():
                context = browser.new_context(viewport=viewport)
                page = context.new_page()
                logged_in, current_path = login(page, role, case)
                if not logged_in:
                    results.append({"scope": role, "viewport": viewport_name, "route": "/login", "ok": False, "failures": [f"login redirected to {current_path}"]})
                    context.close()
                    continue

                for route in case["routes"]:
                    results.append(audit_page(page, route, viewport_name, role))
                context.close()

        browser.close()

    output_path = ARTIFACT_DIR / "responsive-audit.json"
    output_path.write_text(json.dumps(results, indent=2), encoding="utf-8")

    failures = [result for result in results if not result.get("ok")]
    print(f"Audited {len(results)} route/viewport cases")
    print(f"Passed: {len(results) - len(failures)}")
    print(f"Failed: {len(failures)}")
    for failure in failures:
        print(f"FAIL [{failure.get('viewport')}] [{failure.get('scope')}] {failure.get('route')}: {'; '.join(failure.get('failures', []))}")

    return 1 if failures else 0


if __name__ == "__main__":
    sys.exit(main())
