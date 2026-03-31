// Service Worker IlmiShop - Push Notification Stable Version
const CACHE_NAME = 'ilmishop-v1';

// INSTALL
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

// ACTIVATE
self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// PUSH EVENT
self.addEventListener('push', (event) => {
    let data = {};

    // Hindari error kalau payload kosong
    if (event.data) {
        try {
            data = event.data.json();
        } catch (err) {
            data = {
                title: 'IlmiShop',
                body: event.data.text()
            };
        }
    }

    const title = data.title || 'IlmiShop';
    const options = {
        body: data.body || 'Ada notifikasi baru',
        icon: data.icon || '/icon-192x192.png',
        badge: data.badge || '/badge-72x72.png',
        image: data.image || null,
        tag: data.tag || 'ilmishop-notification',
        requireInteraction: data.requireInteraction ?? true,
        renotify: true,
        actions: data.actions || [
            {
                action: 'open',
                title: 'Buka'
            }
        ],
        data: {
            url: data.url || '/',
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// CLICK NOTIFICATION
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes(urlToOpen) && 'focus' in client) {
                    return client.focus();
                }
            }
            return clients.openWindow(urlToOpen);
        })
    );
});

// OPTIONAL: Handle push subscription change
self.addEventListener('pushsubscriptionchange', (event) => {
    console.log('Push subscription expired');
});