const CACHE_NAME = "note-app-v1";

const urlsToCache = [
    "/",
    "/index.php",
];

// INSTALL
self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

// FETCH
self.addEventListener("fetch", event => {

    // cache API notes
    if (event.request.url.includes("get_notes.php")) {
        event.respondWith(
            fetch(event.request)
                .then(res => {
                    const clone = res.clone();

                    caches.open(CACHE_NAME)
                        .then(cache => cache.put(event.request, clone));

                    return res;
                })
                .catch(() => caches.match(event.request))
        );
        return;
    }

    // cache static
    event.respondWith(
        caches.match(event.request)
            .then(res => res || fetch(event.request))
    );
});