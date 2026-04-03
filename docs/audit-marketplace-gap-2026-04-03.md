# Audit Marketplace vs Acuan Shopee (2026-04-03)

Dokumen ini merangkum hasil audit teknis pada modul utama dan status implementasinya.

## Legend
- ✅ Sudah ada & optimal
- ⚠️ Sudah ada tapi belum optimal
- ❌ Belum ada

## Matriks Fitur

| Domain | Status | Catatan audit singkat |
|---|---|---|
| Produk & katalog | ⚠️ | Sudah ada listing, kategori, moderasi. Perlu ranking dan relevansi search lebih kuat. |
| Search/filter/sorting/rekomendasi | ⚠️ | Search suggest ada, namun rekomendasi personal belum matang. |
| Cart | ✅ | Keranjang + update qty + validasi stok sudah ada. |
| Checkout | ⚠️ | Multi-shop checkout, voucher, ongkir sudah ada. Perlu hardening observability & fallback eksternal API. |
| Order system | ✅ | Order lifecycle, item detail, dispute, escrow, payout sudah tersedia. |
| Order tracking detail | ⚠️ -> ditingkatkan | Ditambah checkpoint granular (gudang, sorting, DC, kurir, delivered) + checkpoint manual seller. |
| Address system | ⚠️ -> ditingkatkan | Ditambah dropdown wilayah dinamis + map interaktif + lat/long + detail alamat. |
| Shipping/ongkir/estimasi | ⚠️ | Kalkulasi ada; perlu kalibrasi SLA carrier real-time dan fallback cache. |
| Payment flow | ⚠️ | Manual transfer + Midtrans sudah ada, perlu rekonsiliasi callback monitoring lebih detail. |
| Wishlist | ⚠️ -> ditingkatkan | Ditambah move to cart per-item dan bulk. |
| Review/rating | ⚠️ | Sudah ada review setelah complete, perlu anti-spam/verified purchase badge yang lebih kuat. |
| Notification | ✅ | Notifikasi in-app + unread badge sudah ada. |
| Seller/Admin system | ✅ | Dashboard, moderation, payout, report, KYC, dispute tersedia. |
| Inventory & variasi | ⚠️ | Variants sudah ada, perlu kontrol konflik stok lintas varian yang lebih ketat. |
| Promo/voucher/diskon | ⚠️ | Voucher, flash sale, shipping discount ada; perlu campaign scheduler dan A/B rule. |
| Chat buyer-seller | ✅ | Conversation, polling, send message tersedia. |
| Return/refund | ⚠️ | Dispute + refund escrow ada; perlu SLA automation dan templating keputusan. |
| Security/validation/rate limit | ⚠️ -> ditingkatkan | Rate limiter endpoint write-heavy + validasi alamat berbasis FormRequest ditambahkan. |
| Performance/scalability | ⚠️ | Perlu cache layer agregasi dan queue tuning untuk lonjakan trafik. |
| UI/UX modern | ⚠️ -> ditingkatkan | UI alamat/tracking diperbaiki, masih perlu konsistensi desain lintas halaman. |

## Perbaikan yang dilakukan pada iterasi ini
1. **Order tracking dibuat lebih granular**: event_code + lokasi + metadata untuk timeline logistik detail.
2. **Auto-seeding milestone tracking saat shipped**: warehouse -> sorting center -> line haul -> destination DC -> courier delivery.
3. **Seller dapat tambah checkpoint manual** untuk update perjalanan paket secara real-time operasional.
4. **Buyer order timeline menampilkan lokasi checkpoint** agar lebih transparan.
5. **Snapshot alamat order diperkaya**: village, detail_address, latitude, longitude.

## Prioritas lanjutan (belum dikerjakan di commit ini)
- Implementasi job polling kurir real-time ke provider tracking untuk sinkronisasi otomatis.
- Search ranking + recommendation engine (click-through dan conversion weighted).
- Dashboard observability (payment callback failure rate, queue lag, SLA pengiriman).
- Stress test & load-test checkout + order placement.
