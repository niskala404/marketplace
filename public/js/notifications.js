// ✅ NOTIFICATION SYSTEM
class IlmiNotification {
    constructor() {
        this.soundEnabled = localStorage.getItem('soundEnabled') !== 'false';
        this.permission = Notification.permission;
        this.container = document.getElementById('notificationContainer');
        this.sounds = {
            default: document.getElementById('notifSound'),
            message: document.getElementById('messageSound')
        };
        
        this.init();
    }
    
    init() {
        // Request permission saat load
        if ('Notification' in window && this.permission === 'default') {
            this.requestPermission();
        }
    }
    
    // ✅ MINTA IZIN NOTIFIKASI
    async requestPermission() {
        if (!('Notification' in window)) {
            console.log('Browser tidak support notifikasi');
            return false;
        }
        
        const permission = await Notification.requestPermission();
        this.permission = permission;
        
        if (permission === 'granted') {
            this.show('Notifikasi diaktifkan', 'Anda akan menerima notifikasi real-time', 'success');
            this.subscribeToPush();
        }
        
        return permission === 'granted';
    }
    
    // ✅ SUBSCRIBE KE PUSH SERVER (untuk device notification)
    async subscribeToPush() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            return;
        }
        
        try {
            const reg = await navigator.serviceWorker.ready;
            const sub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array('YOUR_VAPID_PUBLIC_KEY')
            });
            
            // Kirim subscription ke server Laravel
            await fetch('/api/subscribe-push', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify(sub)
            });
            
        } catch (err) {
            console.error('Push subscription failed:', err);
        }
    }
    
    // ✅ MAIN SOUND + VISUAL NOTIFICATION
    show(title, message, type = 'default', options = {}) {
        // 1. PLAY SOUND
        this.playSound(type);
        
        // 2. SHOW VISUAL NOTIFICATION (in-app)
        this.showVisual(title, message, type, options);
        
        // 3. SHOW DEVICE NOTIFICATION (push)
        if (this.permission === 'granted' && options.push !== false) {
            this.showDeviceNotification(title, message, options);
        }
    }
    
    // ✅ PLAY SOUND
    playSound(type = 'default') {
        if (!this.soundEnabled) return;
        
        const sound = this.sounds[type] || this.sounds.default;
        if (sound) {
            sound.currentTime = 0;
            sound.volume = 0.5;
            sound.play().catch(e => console.log('Sound play failed:', e));
        }
    }
    
    // ✅ VISUAL NOTIFICATION (di pojok kanan atas)
    showVisual(title, message, type = 'default', options = {}) {
        const el = document.createElement('div');
        el.className = `
            pointer-events-auto transform transition-all duration-300 translate-x-full
            max-w-sm w-full bg-white rounded-xl shadow-lg border-l-4 p-4 flex items-start gap-3
            ${type === 'success' ? 'border-emerald-500' : ''}
            ${type === 'error' ? 'border-rose-500' : ''}
            ${type === 'warning' ? 'border-amber-500' : ''}
            ${type === 'message' ? 'border-blue-500' : ''}
            ${!['success','error','warning','message'].includes(type) ? 'border-rose-500' : ''}
        `;
        
        const icons = {
            success: 'check-circle',
            error: 'alert-circle',
            warning: 'alert-triangle',
            message: 'message-circle',
            default: 'bell'
        };
        
        el.innerHTML = `
            <div class="shrink-0">
                <svg class="w-6 h-6 ${type === 'success' ? 'text-emerald-500' : type === 'error' ? 'text-rose-500' : type === 'warning' ? 'text-amber-500' : type === 'message' ? 'text-blue-500' : 'text-rose-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${this.getIconPath(icons[type] || icons.default)}"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-slate-900">${title}</div>
                <div class="text-sm text-slate-500 mt-1">${message}</div>
            </div>
            <button onclick="this.parentElement.remove()" class="shrink-0 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        this.container.appendChild(el);
        
        // Animate in
        requestAnimationFrame(() => {
            el.classList.remove('translate-x-full');
        });
        
        // Auto remove
        setTimeout(() => {
            el.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => el.remove(), 300);
        }, options.duration || 5000);
    }
    
    // ✅ DEVICE NOTIFICATION (Push ke OS)
    showDeviceNotification(title, body, options = {}) {
        if (!('serviceWorker' in navigator)) return;
        
        navigator.serviceWorker.ready.then(reg => {
            reg.showNotification(title, {
                body: body,
                icon: options.icon || '/icon-192x192.png',
                badge: '/badge-72x72.png',
                image: options.image,
                tag: options.tag || 'ilmishop-notif',
                requireInteraction: options.requireInteraction || false,
                actions: options.actions || [],
                data: {
                    url: options.url || '/',
                    ...options.data
                }
            });
        });
    }
    
    // ✅ TOGGLE SOUND
    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        localStorage.setItem('soundEnabled', this.soundEnabled);
        return this.soundEnabled;
    }
    
    // Helper: Icon paths
    getIconPath(name) {
        const paths = {
            'check-circle': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'alert-circle': 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'alert-triangle': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'message-circle': 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'bell': 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'
        };
        return paths[name] || paths.bell;
    }
    
    // Helper: VAPID key conversion
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
}

// Global instance
const ilmiNotif = new IlmiNotification();

// ✅ REAL-TIME NOTIFICATION (Laravel Echo / Pusher)
function initRealTimeNotifications() {
    if (typeof window.Echo === 'undefined') return;
    
    // Notifikasi umum
    Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            ilmiNotif.show(
                notification.title || 'Notifikasi Baru',
                notification.body || notification.message,
                notification.type || 'default',
                {
                    url: notification.url || '/notifications',
                    icon: notification.icon,
                    push: true
                }
            );
        });
    
    // Notifikasi chat/message
    Echo.private(`chat.${userId}`)
        .listen('NewMessage', (e) => {
            ilmiNotif.show(
                e.sender_name || 'Pesan Baru',
                e.message || 'Anda menerima pesan baru',
                'message',
                {
                    url: `/chat/${e.conversation_id}`,
                    push: true
                }
            );
        });
    
    // Notifikasi order
    Echo.channel('orders')
        .listen('OrderStatusUpdated', (e) => {
            if (e.user_id === userId) {
                ilmiNotif.show(
                    'Update Pesanan',
                    `Pesanan #${e.order_id} ${e.status}`,
                    'success',
                    {
                        url: `/orders/${e.order_id}`,
                        push: true
                    }
                );
            }
        });
}

// Init saat DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Expose global
    window.ilmiNotif = ilmiNotif;
    window.initRealTimeNotifications = initRealTimeNotifications;
    
    // Coba init real-time jika user sudah login
    if (typeof userId !== 'undefined') {
        initRealTimeNotifications();
    }
});