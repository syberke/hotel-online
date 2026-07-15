(() => {
    let deferredInstallPrompt = null;
    let registration = null;

    function setStyles(element, styles) {
        Object.assign(element.style, styles);
    }

    function showConnectionStatus(isOnline) {
        let banner = document.getElementById('oasis-pwa-connection-status');

        if (!banner) {
            banner = document.createElement('div');
            banner.id = 'oasis-pwa-connection-status';
            banner.setAttribute('role', 'status');
            banner.setAttribute('aria-live', 'polite');
            document.body.appendChild(banner);
        }

        banner.textContent = isOnline ? 'Back online' : 'Offline mode';
        setStyles(banner, {
            position: 'fixed',
            left: '50%',
            top: '16px',
            transform: 'translateX(-50%)',
            zIndex: '99999',
            padding: '9px 14px',
            border: '1px solid',
            borderColor: isOnline ? '#a7f3d0' : '#fcd34d',
            background: isOnline ? '#ecfdf5' : '#fffbeb',
            color: isOnline ? '#047857' : '#92400e',
            fontSize: '10px',
            fontWeight: '700',
            letterSpacing: '.14em',
            textTransform: 'uppercase',
            boxShadow: '0 8px 24px rgba(0,0,0,.08)',
        });

        window.clearTimeout(showConnectionStatus.timeoutId);
        showConnectionStatus.timeoutId = window.setTimeout(() => banner.remove(), isOnline ? 2400 : 5000);
    }

    function ensureInstallButton() {
        let button = document.getElementById('oasis-pwa-install');

        if (button) {
            return button;
        }

        button = document.createElement('button');
        button.id = 'oasis-pwa-install';
        button.type = 'button';
        button.textContent = 'Install Oasis App';
        button.setAttribute('aria-label', 'Install Oasis Hotel app');
        setStyles(button, {
            position: 'fixed',
            right: '16px',
            bottom: '16px',
            zIndex: '99998',
            border: '1px solid #d4a72c',
            background: '#0a0a0a',
            color: '#ffffff',
            padding: '12px 16px',
            fontSize: '10px',
            fontWeight: '700',
            letterSpacing: '.14em',
            textTransform: 'uppercase',
            cursor: 'pointer',
            boxShadow: '0 14px 36px rgba(0,0,0,.2)',
        });

        button.addEventListener('click', async () => {
            if (!deferredInstallPrompt) {
                return;
            }

            deferredInstallPrompt.prompt();
            await deferredInstallPrompt.userChoice;
            deferredInstallPrompt = null;
            button.remove();
        });

        document.body.appendChild(button);
        return button;
    }

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredInstallPrompt = event;
        ensureInstallButton();
    });

    window.addEventListener('appinstalled', () => {
        deferredInstallPrompt = null;
        document.getElementById('oasis-pwa-install')?.remove();
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
        async install() {
            if (!deferredInstallPrompt) {
                return false;
            }

            deferredInstallPrompt.prompt();
            const result = await deferredInstallPrompt.userChoice;
            deferredInstallPrompt = null;
            document.getElementById('oasis-pwa-install')?.remove();
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
