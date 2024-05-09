if (typeof preLoad !== 'undefined') {
    const preLoad = function () {
        return caches.open("offline").then(function (cache) {
            // caching index and important routes
            return cache.addAll(filesToCache);
        });
    };
}

self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

if (typeof filesToCache !== 'undefined') {
    const filesToCache = [
        '/',
        '/offline'
    ];
}

if (typeof checkResponse !== 'undefined') {
    const checkResponse = function (request) {
        return new Promise(function (fulfill, reject) {
            fetch(request).then(function (response) {
                if (response.status !== 404) {
                    fulfill(response);
                } else {
                    reject();
                }
            }, reject);
        });
    };
}

if (typeof addToCache !== 'undefined') {
    const addToCache = function (request) {
        return caches.open("offline").then(function (cache) {
            return fetch(request).then(function (response) {
                return cache.put(request, response);
            });
        });
    };

}
if (typeof returnFromCache !== 'undefined') {
    const returnFromCache = function (request) {
        return caches.open("offline").then(function (cache) {
            return cache.match(request).then(function (matching) {
                if (!matching || matching.status === 404) {
                    return cache.match("offline");
                } else {
                    return matching;
                }
            });
        });
    };
}

self.addEventListener("fetch", function (event) {
    event.respondWith(checkResponse(event.request).catch(function () {
        return returnFromCache(event.request);
    }));
    if (!event.request.url.startsWith('http')) {
        event.waitUntil(addToCache(event.request));
    }
});
