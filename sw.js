const SW_VERSION = "formaibly-v1";
const APP_SHELL_CACHE = `${SW_VERSION}-app-shell`;
const STATIC_CACHE = `${SW_VERSION}-static`;
const OFFLINE_URL = "/offline.html";

const APP_SHELL_FILES = [
  "/",
  OFFLINE_URL,
  "/manifest.json",
  "/assets/common/css/bootstrap.min.css",
  "/assets/common/css/all.min.css",
  "/assets/common/css/line-awesome.min.css",
  "/assets/common/js/jquery-3.7.1.min.js",
  "/assets/common/js/bootstrap.bundle.min.js",
  "/assets/presets/default/css/main.css",
  "/assets/presets/default/css/custom.css",
  "/assets/presets/default/js/main.js",
  "/assets/pwa/icon-192x192.svg",
  "/assets/pwa/icon-512x512.svg"
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(APP_SHELL_CACHE).then((cache) => cache.addAll(APP_SHELL_FILES)).catch(() => Promise.resolve())
  );
  self.skipWaiting();
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key !== APP_SHELL_CACHE && key !== STATIC_CACHE)
          .map((key) => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

function isSameOrigin(requestUrl) {
  return new URL(requestUrl).origin === self.location.origin;
}

function isApiRequest(url) {
  return url.pathname.startsWith("/api") || url.pathname.includes("/ajax");
}

self.addEventListener("fetch", (event) => {
  const { request } = event;
  const url = new URL(request.url);

  if (request.method !== "GET") {
    return;
  }

  if (!isSameOrigin(request.url)) {
    return;
  }

  // Keep API/network requests live and uncached to avoid stale data issues.
  if (isApiRequest(url)) {
    event.respondWith(fetch(request));
    return;
  }

  if (request.mode === "navigate") {
    event.respondWith(
      fetch(request)
        .then((response) => {
          const copy = response.clone();
          caches.open(APP_SHELL_CACHE).then((cache) => cache.put(request, copy)).catch(() => Promise.resolve());
          return response;
        })
        .catch(async () => {
          const cache = await caches.open(APP_SHELL_CACHE);
          return (await cache.match(request)) || (await cache.match(OFFLINE_URL));
        })
    );
    return;
  }

  const isStaticAsset =
    ["style", "script", "image", "font"].includes(request.destination) ||
    url.pathname.startsWith("/assets/");

  if (isStaticAsset) {
    event.respondWith(
      caches.match(request).then((cachedResponse) => {
        const fetchPromise = fetch(request)
          .then((networkResponse) => {
            const responseCopy = networkResponse.clone();
            caches.open(STATIC_CACHE).then((cache) => cache.put(request, responseCopy)).catch(() => Promise.resolve());
            return networkResponse;
          })
          .catch(() => cachedResponse);

        return cachedResponse || fetchPromise;
      })
    );
  }
});
