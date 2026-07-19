(() => {
    const DISMISS_KEY = 'oasis-pwa-install-dismissed-until';
    const DISMISS_DURATION = 3 * 24 * 60 * 60 * 1000;
    const DASHBOARD_ONLINE_NOTICE_DURATION = 10000;
    const PORTAL_LABELS = {
        guest: 'Guest',
        admin: 'Admin',
        manager: 'Manager',
        receptionist: 'Receptionist',
        dashboard: 'Hotel',
    };

    let deferredInstallPrompt = null;
    let registration = null;
    let dashboardNoticeTimer = null;
    let connectionWasOffline = !navigator.onLine;

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

    function portalLabel(portal) {
        return PORTAL_LABELS[portal] || PORTAL_LABELS.dashboard;
    }

    function offlineMessage(portal) {
        const messages = {
            guest: 'Data reservasi, tagihan, dan layanan mungkin belum terbaru. Pesanan atau pembayaran tidak akan dikirim sampai koneksi kembali.',
            admin: 'Data operasional mungkin belum terbaru. Tambah, ubah, hapus, dan pembaruan status tidak akan dikirim sampai koneksi kembali.',
            manager: 'Laporan dan data operasional mungkin belum terbaru. Ekspor serta perubahan data memerlukan koneksi aktif.',
            receptionist: 'Check-in, check-out, pembayaran, penetapan kamar, dan pembaruan status tidak akan dikirim sampai koneksi kembali.',
            dashboard: 'Data yang terlihat mungkin belum terbaru. Aksi operasional tidak akan dikirim sampai koneksi kembali.',
        };

        return messages[portal] || messages.dashboard;
    }

    function setConnectionPills(state) {
        document.querySelectorAll('[data-oasis-connection-pill]').forEach((pill) => {
            pill.dataset.state = state;

            const label = pill.querySelector('[data-oasis-connection-pill-label]');
            if (label) {
                label.textContent = state === 'online'
                    ? 'Online'
                    : state === 'checking'
                        ? 'Checking'
                        : 'Offline';
            }
        });
    }

    function setDashboardConnectionState(state, options = {}) {
        window.clearTimeout(dashboardNoticeTimer);
        setConnectionPills(state);

        document.querySelectorAll('[data-oasis-dashboard-connection]').forEach((banner) => {
            const portal = banner.dataset.portal || 'dashboard';
            const label = portalLabel(portal);
            const eyebrow = banner.querySelector('[data-oasis-connection-eyebrow]');
            const title = banner.querySelector('[data-oasis-connection-title]');
            const message = banner.querySelector('[data-oasis-connection-message]');
            const badge = banner.querySelector('[data-oasis-connection-badge]');
            const icon = banner.querySelector('[data-oasis-connection-icon]');
            const retryButton = banner.querySelector('[data-oasis-connection-retry]');

            banner.dataset.state = state;

            if (state === 'online') {
                banner.hidden = options.showBanner === false;
                if (eyebrow) eyebrow.textContent = 'BACK ONLINE';
                if (title) title.textContent = `Dashboard ${label} tersambung kembali`;
                if (message) message.textContent = 'Koneksi sudah aktif. Muat ulang halaman untuk mengambil data terbaru dari server.';
                if (badge) badge.textContent = 'Online';
                if (icon) icon.className = 'fa-solid fa-wifi';
                if (retryButton) {
                    retryButton.disabled = false;
                    retryButton.textContent = 'Muat ulang data';
                }
                return;
            }

            banner.hidden = false;

            if (state === 'checking') {
                if (eyebrow) eyebrow.textContent = 'CHECKING CONNECTION';
                if (title) title.textContent = `Memeriksa koneksi Dashboard ${label}`;
                if (message) message.textContent = 'Sistem sedang mencoba menghubungi server Oasis Hotel.';
                if (badge) badge.textContent = 'Checking';
                if (icon) icon.className = 'fa-solid fa-arrows-rotate fa-spin';
                if (retryButton) {
                    retryButton.disabled = true;
                    retryButton.textContent = 'Memeriksa...';
                }
                return;
            }

            if (eyebrow) eyebrow.textContent = 'OFFLINE MODE';
            if (title) title.textContent = options.title || `Dashboard ${label} sedang offline`;
            if (message) message.textContent = options.message || offlineMessage(portal);
            if (badge) badge.textContent = 'Offline';
            if (icon) icon.className = 'fa-solid fa-wifi';
            if (retryButton) {
                retryButton.disabled = false;
                retryButton.textContent = 'Coba lagi';
            }
        });

        if (state === 'online' && options.showBanner !== false) {
            dashboardNoticeTimer = window.setTimeout(() => {
                document.querySelectorAll('[data-oasis-dashboard-connection]').forEach((banner) => {
                    banner.hidden = true;
                });
            }, DASHBOARD_ONLINE_NOTICE_DURATION);
        }
    }

    async function verifyDashboardConnection(button) {
        const banner = button.closest('[data-oasis-dashboard-connection]');

        if (banner?.dataset.state === 'online') {
            window.location.reload();
            return;
        }

        setDashboardConnectionState('checking');

        const controller = new AbortController();
        const timeoutId = window.setTimeout(() => controller.abort(), 6000);

        try {
            const response = await fetch(`/up?connection_check=${Date.now()}`, {
                method: 'GET',
                cache: 'no-store',
                credentials: 'same-origin',
                headers: {
                    Accept: 'text/plain, application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: controller.signal,
            });

            if (!response.ok) {
                throw new Error(`Connection check failed with HTTP ${response.status}`);
            }

            connectionWasOffline = false;
            setDashboardConnectionState('online', { showBanner: true });
        } catch (error) {
            connectionWasOffline = true;
            setDashboardConnectionState('offline', {
                title: 'Koneksi belum kembali',
                message: 'Server belum dapat dihubungi. Periksa Wi-Fi, kabel jaringan, atau koneksi ke VM lalu coba lagi.',
            });
        } finally {
            window.clearTimeout(timeoutId);
        }
    }

    function initializeDashboardConnectionStatus() {
        setDashboardConnectionState(navigator.onLine ? 'online' : 'offline', {
            showBanner: !navigator.onLine,
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
            const installButton = event.currentTarget;

            if (!deferredInstallPrompt) {
                dismissInstallCard(false);
                return;
            }

            installButton.disabled = true;
            installButton.textContent = 'Opening install…';

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

    window.addEventListener('online', () => {
        showConnectionStatus(true);
        setDashboardConnectionState('online', { showBanner: connectionWasOffline });
        connectionWasOffline = false;
    });

    window.addEventListener('offline', () => {
        connectionWasOffline = true;
        showConnectionStatus(false);
        setDashboardConnectionState('offline');
    });

    document.addEventListener('click', (event) => {
        const retryButton = event.target.closest('[data-oasis-connection-retry]');
        if (!retryButton) return;

        event.preventDefault();
        verifyDashboardConnection(retryButton);
    });

    window.OasisPWA = {
        get registration() {
            return registration;
        },
        get canInstall() {
            return Boolean(deferredInstallPrompt);
        },
        get isOnline() {
            return navigator.onLine;
        },
        showInstallCard,
        checkConnection() {
            const retryButton = document.querySelector('[data-oasis-connection-retry]');
            return retryButton ? verifyDashboardConnection(retryButton) : Promise.resolve(false);
        },
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeDashboardConnectionStatus, { once: true });
    } else {
        initializeDashboardConnectionStatus();
    }

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
