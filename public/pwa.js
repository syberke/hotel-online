(() => {
    const DISMISS_KEY = 'oasis-pwa-install-dismissed-until';
    const DISMISS_DURATION = 3 * 24 * 60 * 60 * 1000;

    let deferredInstallPrompt = null;
    let registration = null;

    function whenBodyReady(callback) {
        if (document.body) {
            callback();
            return;
        }

        document.addEventListener('DOMContentLoaded', callback, { once: true });
    }

    function wasRecentlyDismissed() {
        const dismissedUntil = Number.parseInt(localStorage.getItem(DISMISS_KEY) || '0', 10);
        return Number.isFinite(dismissedUntil) && dismissedUntil > Date.now();
    }

    function dismissInstallCard(remember = true) {
        if (remember) {
            localStorage.setItem(DISMISS_KEY, String(Date.now() + DISMISS_DURATION));
        }

        document.getElementById('oasis-pwa-install-card')?.remove();
    }

    function showConnectionStatus(isOnline) {
        whenBodyReady(() => {
            let banner = document.getElementById('oasis-pwa-connection-status');

            if (!banner) {
                banner = document.createElement('div');
                banner.id = 'oasis-pwa-connection-status';
                banner.setAttribute('role', 'status');
                banner.setAttribute('aria-live', 'polite');
                document.body.appendChild(banner);
            }

            banner.dataset.online = String(isOnline);
            banner.textContent = isOnline ? 'Connection restored' : 'You are currently offline';

            window.clearTimeout(showConnectionStatus.timeoutId);
            showConnectionStatus.timeoutId = window.setTimeout(
                () => banner.remove(),
                isOnline ? 2600 : 5200,
            );
        });
    }

    function buildInstallCard() {
        const card = document.createElement('aside');
        card.id = 'oasis-pwa-install-card';
        card.setAttribute('role', 'dialog');
        card.setAttribute('aria-modal', 'false');
        card.setAttribute('aria-labelledby', 'oasis-pwa-install-title');
        card.innerHTML = `
            <div class="oasis-pwa-card__accent"></div>
            <div class="oasis-pwa-card__body">
                <div class="oasis-pwa-card__header">
                    <div class="oasis-pwa-card__logo">
                        <img src="/logo.svg" alt="Oasis Hotel & Resort">
                    </div>
                    <div class="oasis-pwa-card__copy">
                        <p class="oasis-pwa-card__eyebrow">Oasis app</p>
                        <h2 class="oasis-pwa-card__title" id="oasis-pwa-install-title">Install for quicker access</h2>
                        <p class="oasis-pwa-card__description">Open the hotel portal from your home screen with a clean standalone app experience.</p>
                    </div>
                    <button type="button" class="oasis-pwa-card__close" aria-label="Close install suggestion">×</button>
                </div>
                <div class="oasis-pwa-card__benefits" aria-hidden="true">
                    <div class="oasis-pwa-card__benefit">Fast launch</div>
                    <div class="oasis-pwa-card__benefit">Standalone view</div>
                    <div class="oasis-pwa-card__benefit">Offline public pages</div>
                </div>
                <div class="oasis-pwa-card__actions">
                    <button type="button" class="oasis-pwa-card__install">Install Oasis</button>
                    <button type="button" class="oasis-pwa-card__later">Later</button>
                </div>
            </div>
        `;

        card.querySelector('.oasis-pwa-card__close')?.addEventListener('click', () => dismissInstallCard(true));
        card.querySelector('.oasis-pwa-card__later')?.addEventListener('click', () => dismissInstallCard(true));
        card.querySelector('.oasis-pwa-card__install')?.addEventListener('click', async (event) => {
            const button = event.currentTarget;

            if (!deferredInstallPrompt) {
                dismissInstallCard(false);
                return;
            }

            button.disabled = true;
            button.textContent = 'Opening install…';

            try {
                deferredInstallPrompt.prompt();
                const result = await deferredInstallPrompt.userChoice;

                if (result.outcome === 'accepted') {
                    localStorage.removeItem(DISMISS_KEY);
                } else {
                    localStorage.setItem(DISMISS_KEY, String(Date.now() + DISMISS_DURATION));
                }
            } finally {
                deferredInstallPrompt = null;
                card.remove();
            }
        });

        return card;
    }

    function showInstallCard() {
        if (!deferredInstallPrompt || wasRecentlyDismissed()) {
            return null;
        }

        let card = document.getElementById('oasis-pwa-install-card');
        if (card) {
            return card;
        }

        card = buildInstallCard();
        whenBodyReady(() => document.body.appendChild(card));
        return card;
    }

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredInstallPrompt = event;
        showInstallCard();
    });

    window.addEventListener('appinstalled', () => {
        deferredInstallPrompt = null;
        localStorage.removeItem(DISMISS_KEY);
        document.getElementById('oasis-pwa-install-card')?.remove();
    });

    window.addEventListener('online', () => showConnectionStatus(true));
    window.addEventListener('offline', () => showConnectionStatus(false));

    window.OasisPWA = {
        get registration() {
            return registration;
        },
        get canInstall() {
            return Boolean(deferredInstallPrompt);
        },
        showInstallCard,
        async install() {
            if (!deferredInstallPrompt) {
                return false;
            }

            deferredInstallPrompt.prompt();
            const result = await deferredInstallPrompt.userChoice;
            deferredInstallPrompt = null;
            document.getElementById('oasis-pwa-install-card')?.remove();
            return result.outcome === 'accepted';
        },
    };

    window.addEventListener('load', async () => {
        if (!('serviceWorker' in navigator) || !window.isSecureContext) {
            return;
        }

        try {
            registration = await navigator.serviceWorker.register('/sw.js', { scope: '/' });
        } catch (error) {
            console.error('Oasis PWA service worker registration failed.', error);
        }
    });
})();
