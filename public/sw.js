const CACHE_VERSION = 'oasis-pwa-v4';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const PAGE_CACHE = `${CACHE_VERSION}-public-pages`;

const CORE_ASSETS = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/pwa.js',
    '/pwa-ui.css',
    '/logo.svg',
    '/icons/oasis-pwa.svg',
    '/icons/oasis-maskable.svg',
];

const PUBLIC_NAVIGATION_PATHS = new Set([
    '/',
    '/rooms',
    '/restaurant',
    '/facilities',
    '/contact',
]);

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => cache.addAll(CORE_ASSETS))
            .then(() => self.skipWaiting()),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys
                    .filter((key) => !key.startsWith(CACHE_VERSION))
                    .map((key) => caches.delete(key)),
            ))
            .then(() => self.clients.claim()),
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    if (url.pathname === '/sw.js') {
        return;
    }

    if (request.mode === 'navigate') {
        if (PUBLIC_NAVIGATION_PATHS.has(url.pathname)) {
            event.respondWith(networkFirstPublicPage(event, request, url));
            return;
        }

        event.respondWith(networkOnlyPrivatePage(request));
        return;
    }

    if (isPublicStaticAsset(url)) {
        event.respondWith(staleWhileRevalidate(request));
    }
});

async function networkFirstPublicPage(event, request, url) {
    try {
        const response = await fetch(request);

        if (response.ok) {
            event.waitUntil(cachePublicSnapshot(url));
        }

        return response;
    } catch (error) {
        const cached = await caches.match(request, { ignoreSearch: true });
        return cached || caches.match('/offline.html');
    }
}

async function cachePublicSnapshot(url) {
    try {
        const snapshotRequest = new Request(`${url.origin}${url.pathname}${url.search}`, {
            method: 'GET',
            headers: { Accept: 'text/html' },
            credentials: 'omit',
            cache: 'no-store',
        });
        const snapshotResponse = await fetch(snapshotRequest);

        if (!snapshotResponse.ok) {
            return;
        }

        const cache = await caches.open(PAGE_CACHE);
        await cache.put(snapshotRequest, snapshotResponse);
    } catch (error) {
        // The live response has already succeeded. Snapshot caching is best effort.
    }
}

async function networkOnlyPrivatePage(request) {
    try {
        return await fetch(request);
    } catch (error) {
        return caches.match('/offline.html');
    }
}

function isPublicStaticAsset(url) {
    return url.pathname.startsWith('/build/')
        || url.pathname.startsWith('/icons/')
        || url.pathname === '/manifest.json'
        || url.pathname === '/offline.html'
        || url.pathname === '/pwa.js'
        || url.pathname === '/pwa-ui.css'
        || url.pathname === '/logo.svg';
}

async function staleWhileRevalidate(request) {
    const cache = await caches.open(STATIC_CACHE);
    const cached = await cache.match(request);

    const networkPromise = fetch(request)
        .then((response) => {
            if (response.ok) {
                cache.put(request, response.clone());
            }
            return response;
        })
        .catch(() => null);

    if (cached) {
        return cached;
    }

    return (await networkPromise) || new Response('', { status: 504, statusText: 'Offline' });
}
