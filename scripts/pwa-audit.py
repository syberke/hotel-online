import json
import os
import sys
from pathlib import Path
from urllib.parse import urljoin

from playwright.sync_api import sync_playwright

BASE_URL = os.environ.get("BASE_URL", "http://localhost:8080").rstrip("/") + "/"
ARTIFACT_DIR = Path(os.environ.get("PWA_ARTIFACT_DIR", "pwa-audit-artifacts"))
ARTIFACT_DIR.mkdir(parents=True, exist_ok=True)


def fail(message: str) -> None:
    raise AssertionError(message)


def main() -> int:
    results: dict[str, object] = {"base_url": BASE_URL, "checks": []}

    def check(name: str, condition: bool, detail: object = None) -> None:
        results["checks"].append({"name": name, "passed": bool(condition), "detail": detail})
        if not condition:
            fail(f"{name} failed: {detail}")

    try:
        with sync_playwright() as playwright:
            browser = playwright.chromium.launch(headless=True)
            context = browser.new_context(viewport={"width": 390, "height": 844})
            page = context.new_page()
            page_errors: list[str] = []
            page.on("pageerror", lambda error: page_errors.append(str(error)))

            response = page.goto(BASE_URL, wait_until="networkidle", timeout=60_000)
            check("homepage responds", response is not None and response.status == 200, response.status if response else None)
            check("secure context", page.evaluate("() => window.isSecureContext"), page.url)

            manifest_href = page.locator('link[rel="manifest"]').get_attribute("href")
            check("manifest linked", manifest_href == "/manifest.json", manifest_href)

            manifest_response = context.request.get(urljoin(BASE_URL, "manifest.json"))
            manifest = manifest_response.json()
            check("manifest responds", manifest_response.status == 200, manifest_response.status)
            check("manifest standalone", manifest.get("display") == "standalone", manifest.get("display"))
            check("manifest start URL", manifest.get("start_url") == "/", manifest.get("start_url"))
            check("manifest has icons", len(manifest.get("icons", [])) >= 2, manifest.get("icons"))

            page.wait_for_function(
                """() => navigator.serviceWorker && navigator.serviceWorker.ready.then(() => true)""",
                timeout=60_000,
            )
            page.reload(wait_until="networkidle", timeout=60_000)
            page.wait_for_timeout(1000)

            service_worker_state = page.evaluate(
                """async () => {
                    const registration = await navigator.serviceWorker.ready;
                    return {
                        controller: Boolean(navigator.serviceWorker.controller),
                        active: Boolean(registration.active),
                        scope: registration.scope,
                    };
                }"""
            )
            check("service worker active", service_worker_state["active"], service_worker_state)
            check("page controlled by service worker", service_worker_state["controller"], service_worker_state)
            check("service worker root scope", service_worker_state["scope"].endswith("/"), service_worker_state["scope"])

            rooms_response = page.goto(urljoin(BASE_URL, "rooms"), wait_until="networkidle", timeout=60_000)
            check("rooms responds online", rooms_response is not None and rooms_response.status == 200, rooms_response.status if rooms_response else None)
            page.wait_for_timeout(1200)

            cached_private = page.evaluate(
                """async () => Boolean(await caches.match('/admin/dashboard'))"""
            )
            check("private admin page not cached", not cached_private, cached_private)

            context.set_offline(True)
            offline_rooms = page.goto(urljoin(BASE_URL, "rooms"), wait_until="domcontentloaded", timeout=30_000)
            page.wait_for_timeout(500)
            offline_rooms_text = page.locator("body").inner_text()
            check("visited public page works offline", offline_rooms is not None and offline_rooms.status == 200, offline_rooms.status if offline_rooms else None)
            check("visited public page uses cached page", "Connection unavailable" not in offline_rooms_text, offline_rooms_text[:120])

            offline_contact = page.goto(urljoin(BASE_URL, "contact"), wait_until="domcontentloaded", timeout=30_000)
            page.wait_for_timeout(300)
            offline_contact_text = page.locator("body").inner_text()
            check("uncached public page gets offline fallback", offline_contact is not None and offline_contact.status == 200, offline_contact.status if offline_contact else None)
            check("offline fallback visible", "Connection unavailable" in offline_contact_text, offline_contact_text[:120])

            offline_private = page.goto(urljoin(BASE_URL, "admin/dashboard"), wait_until="domcontentloaded", timeout=30_000)
            page.wait_for_timeout(300)
            offline_private_text = page.locator("body").inner_text()
            check("private portal gets offline fallback", offline_private is not None and offline_private.status == 200, offline_private.status if offline_private else None)
            check("private portal is not exposed from cache", "Connection unavailable" in offline_private_text, offline_private_text[:120])

            context.set_offline(False)
            check("no uncaught page errors", not page_errors, page_errors)

            page.screenshot(path=str(ARTIFACT_DIR / "offline-fallback-mobile.png"), full_page=True)
            browser.close()

        results["passed"] = True
        print("PWA audit passed")
        for item in results["checks"]:
            print(f"PASS: {item['name']}")
        return 0
    except Exception as error:
        results["passed"] = False
        results["error"] = str(error)
        print(f"PWA audit failed: {error}", file=sys.stderr)
        return 1
    finally:
        (ARTIFACT_DIR / "pwa-audit.json").write_text(json.dumps(results, indent=2), encoding="utf-8")


if __name__ == "__main__":
    sys.exit(main())
