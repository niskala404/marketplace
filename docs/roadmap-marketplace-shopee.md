# Roadmap Pengembangan Marketplace (Arah Fitur ala Shopee)

Dokumen ini dipakai sebagai acuan bertahap agar pengembangan terukur, terutama untuk **fitur inti** dan **keamanan user**.

## 1) Prioritas 0 (Wajib Minggu Ini)

### Keamanan akun
- Aktifkan verifikasi email wajib sebelum transaksi.
- Tambahkan 2FA (OTP aplikasi/email) untuk aksi sensitif: ganti password, ubah rekening payout, tarik saldo.
- Terapkan pembatasan request (rate limit) pada endpoint write-heavy (cart, checkout, chat, report, review).

### Keamanan transaksi
- Pastikan status order hanya bisa berpindah lewat state machine yang valid.
- Validasi ulang total belanja, ongkir, diskon, dan voucher di server (jangan percaya hitungan dari client).
- Simpan jejak audit untuk aksi admin/seller penting (approve/refund/kyc).

## 2) Prioritas 1 (2-4 Minggu)

### Fitur pengalaman belanja
- Pencarian yang lebih cerdas: typo tolerance + ranking berdasarkan konversi.
- Rekomendasi produk personal berbasis kategori yang sering dilihat/beli.
- Penguatan halaman produk: varian, FAQ, video produk, estimasi kirim real-time.

### Fitur kepercayaan user
- Badge toko terverifikasi + SLA chat.
- Deteksi review spam/fake dan validasi pembelian terverifikasi.
- Dashboard sengketa (dispute) yang transparan: timeline + bukti + status otomatis.

## 3) Prioritas 2 (1-2 Bulan)

### Growth & retention
- Loyalty point/coin dengan aturan anti-abuse.
- Gamifikasi campaign (flash sale, voucher misi, free shipping bertingkat).
- Affiliate dan referral dengan anti-fraud (device fingerprint + velocity checks).

### Operasional seller
- Bulk upload produk + sinkronisasi stok.
- Performa iklan produk (boost) dengan pelaporan ROI sederhana.
- Analitik seller: funnel view -> add to cart -> checkout -> paid.

## 4) KPI yang wajib dipantau
- Conversion rate (CVR)
- Cart abandonment rate
- Fraud rate (per 1.000 transaksi)
- Chargeback/refund ratio
- Mean time to resolve dispute
- Uptime checkout & payment callback success rate

## 5) Checklist keamanan minimum
- [ ] Rate limiting untuk endpoint publik dan endpoint tulis.
- [ ] CSRF aktif untuk semua aksi state-changing (kecuali webhook resmi).
- [ ] Password hashing modern (bcrypt/argon2) + kebijakan password kuat.
- [ ] Validasi file upload (mime, ukuran, antivirus scanning bila memungkinkan).
- [ ] Logging keamanan terpusat + alert anomali.
- [ ] Backup rutin + uji restore.

## Catatan implementasi terbaru
- Endpoint publik pelaporan (`/report`) sudah dibatasi dengan limiter khusus.
- Endpoint write-heavy user (follow, chat, wishlist, cart, checkout, review, dispute, payment proof, cancel/confirm order) sudah ditambah throttling.
