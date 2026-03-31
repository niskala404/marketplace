import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// --- Shopee-like search suggestions (no dependencies) ---
(() => {
  const input = document.getElementById('searchInput');
  const box = document.getElementById('searchSuggest');
  const list = document.getElementById('searchSuggestList');
  if (!input || !box || !list) return;

  let t = null;
  let lastQ = '';
  let controller = null;

  const hide = () => {
    box.classList.add('hidden');
  };
  const show = () => {
    box.classList.remove('hidden');
  };
  const esc = (s) => (s || '').toString().replace(/[&<>"']/g, (m) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[m]));

  const render = (data, q) => {
    const products = (data && data.products) ? data.products : [];
    const categories = (data && data.categories) ? data.categories : [];

    if (products.length === 0 && categories.length === 0) {
      list.innerHTML = `<div class="px-2 py-3 text-sm text-slate-500">Tidak ada saran untuk <span class="font-semibold">${esc(q)}</span>.</div>`;
      show();
      return;
    }

    const rows = [];
    if (products.length) {
      rows.push(`<div class="px-2 py-1 text-[11px] uppercase tracking-wide text-slate-400">Produk</div>`);
      products.forEach((p) => {
        rows.push(
          `<a class="block px-2 py-2 text-sm hover:bg-slate-50 rounded-xl" href="/p/${encodeURIComponent(p.slug)}">
              <div class="flex items-center justify-between gap-2">
                <span class="text-slate-800">${esc(p.name)}</span>
                <span class="text-xs text-slate-400">Lihat</span>
              </div>
           </a>`
        );
      });
    }
    if (categories.length) {
      rows.push(`<div class="mt-1 px-2 py-1 text-[11px] uppercase tracking-wide text-slate-400">Kategori</div>`);
      categories.forEach((c) => {
        rows.push(
          `<a class="block px-2 py-2 text-sm hover:bg-slate-50 rounded-xl" href="/?category=${encodeURIComponent(c.id)}&q=${encodeURIComponent(q)}">
              <div class="flex items-center justify-between gap-2">
                <span class="text-slate-800">${esc(c.name)}</span>
                <span class="text-xs text-slate-400">Cari</span>
              </div>
           </a>`
        );
      });
    }

    list.innerHTML = rows.join('');
    show();
  };

  const fetchSuggest = async (q) => {
    if (controller) controller.abort();
    controller = new AbortController();
    const url = `/search/suggest?q=${encodeURIComponent(q)}`;
    const res = await fetch(url, { headers: { 'Accept': 'application/json' }, signal: controller.signal });
    if (!res.ok) throw new Error('failed');
    return await res.json();
  };

  input.addEventListener('input', () => {
    const q = (input.value || '').trim();
    if (q.length < 2) {
      hide();
      return;
    }
    if (q === lastQ) return;
    lastQ = q;

    clearTimeout(t);
    t = setTimeout(async () => {
      try {
        const data = await fetchSuggest(q);
        render(data, q);
      } catch (e) {
        // silent
      }
    }, 180);
  });

  document.addEventListener('click', (e) => {
    if (!box.contains(e.target) && e.target !== input) hide();
  });

  input.addEventListener('focus', () => {
    const q = (input.value || '').trim();
    if (q.length >= 2 && list.innerHTML.trim() !== '') show();
  });
})();
async function enablePushNotifications() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    const permission = await Notification.requestPermission();
    if (permission !== 'granted') return;

    const reg = await navigator.serviceWorker.register('/sw.js');

    // Jika pakai FCM / VAPID, di sini baru buat subscription/token
    console.log('Service worker ready:', reg);
}

window.addEventListener('load', enablePushNotifications);