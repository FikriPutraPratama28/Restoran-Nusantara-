# Changelog Audit & Cleanup — Restoran Nusantara

Branch: `audit-cleanup-20260703` (baseline aman ada di `master`, semua bisa di-rollback).

> Catatan lingkungan: PHP/Composer tidak tersedia di sandbox audit, sehingga `php -l`, PHPStan,
> `composer audit`, migrasi, dan PHPUnit **belum dijalankan runtime**. Verifikasi dilakukan lewat
> pembacaan kode + grep + pengecekan keseimbangan brace/paren. Jalankan verifikasi runtime di mesin
> lokal (lihat bagian "Verifikasi yang perlu Anda jalankan").

## 1. Keamanan (Critical/High) — DIPERBAIKI

- **Hapus backdoor login admin** (`Admin/DashboardController::loginPost`). Kredensial hardcoded
  `admin@warung.id` / `admin123` + auto-create akun admin dihilangkan total. Login kini murni via `Auth::attempt`.
- **Tutup bypass otorisasi session legacy**:
  - `PermissionMiddleware`: jalur "session admin = akses penuh" (melewati cek permission) dihapus; kini wajib `Auth::check()`.
  - `AdminAuth`: pemulihan Auth dari session legacy hanya jika user valid, ber-role `admin`/`super_admin`, dan aktif; jika tidak, session dibersihkan. Default email `admin@warung.id` dihapus.
- **Rate limit login tahan `config:cache`**: `ThrottleLogin` tidak lagi memanggil `env()` saat runtime (yang jadi `null` setelah cache dan mengunci semua login). Ditambah `config/security.php` dan dibaca via `config('security.rate_limit_login')`.
- **Stop kebocoran pesan exception** ke client pada `ReservationController::store`; error asli dicatat ke log, client menerima pesan generik.
- **Header keamanan ke semua response**: `SecurityHeaders` sebelumnya hanya untuk `Illuminate\Http\Response` (JSON & redirect tidak dapat header). Kini diterapkan ke semua response kecuali streamed/binary.

## 2. Bug — DIPERBAIKI

- **Route `karyawan.dashboard` yang tidak ada** menyebabkan potensi `RouteNotFoundException` di
  `RoleMiddleware` & `RedirectIfAuthenticated` (fitur karyawan sudah dinonaktifkan). Redirect diarahkan ke `home`.
- **Schema vs validasi `customer_email`**: kolom `NOT NULL` padahal validasi `nullable`. Ditambah migrasi
  `2026_07_03_100000_make_customer_email_nullable_on_reservations_table.php` (raw ALTER khusus MySQL, tanpa doctrine/dbal).
- **Guard null id** saat menghitung menu terlaris (item tanpa `id` kini dilewati, tidak menimbulkan warning/null-key).

## 3. Performa — DIPERBAIKI

- Eager-load relasi user pada `recentReservations` di dashboard (`Reservation::with('user')`) untuk mencegah N+1 di view.

## 4. Arsitektur / Duplikasi — DIPERBAIKI

- `DashboardController`: ekstrak helper `tallyOrderedItems()` & `tallyMenuCounts()` — menghapus ~5 blok
  loop agregasi `ordered_items` dan 2 blok penghitungan menu yang identik.
- `MenuController`: ekstrak `sanitizePriceInputs()` — menghapus 2 blok sanitasi harga duplikat.

## 5. Cleanup — DIKARANTINA ke `_deprecated/` (belum dihapus permanen)

Lihat `_deprecated/README.md` untuk tabel lengkap + bukti. Ringkas:

- `AuthController.php` (tidak dirutekan) + import matinya di `routes/web.php`.
- View yatim: `auth/login`, `auth/register`, `auth/profile`, `welcome`.
- Artefak dev: `check_html.php`, `console_log_error.md`, folder `.kombai/` & `.qoder/`, dan ~22 screenshot PNG di root (termasuk file 3,1 MB & 2,3 MB).

**Konfirmasi diperlukan** sebelum hapus permanen isi `_deprecated/`.

## 6. Belum dikerjakan / butuh review manual

| Item | Prioritas | Alasan ditunda |
|---|---|---|
| Policy/Gate untuk resource sensitif (User/Reservation) | Medium | Perubahan arsitektural; berisiko regresi tanpa test runtime. Otorisasi saat ini via middleware role/permission + cek kepemilikan manual. |
| Duplikat timestamp migrasi `2024_01_01_000005` (facilities & gallery_images) | Medium | **Jangan rename bila sudah di-apply ke production** (mengubah nama = migrasi dianggap belum jalan). Perlu keputusan Anda. |
| `.env.example` `APP_DEBUG=true` | Medium | File `.env*` termasuk yang tidak boleh disentuh (aturan #3). Default runtime aman (`config/app.php` = false). Ubah manual bila perlu. |
| CSP `unsafe-inline`/`unsafe-eval` di `SecurityHeaders` | Low | Dipakai Alpine.js + skrip inline; memperketat butuh refactor frontend (kemungkinan disengaja). |
| Blade `{!! !!}` (ikon SVG) | Low | Sumber dari array di kode, bukan input user. Aman selama tetap statis. |
| Test suite riil (kini hanya `ExampleTest`) | Low | Rekomendasi; tambah smoke test auth/menu/reservasi. |
| Upgrade Laravel 10 -> 11/12 | Review | Rencana lengkap di `docs/UPGRADE-PLAN-laravel.md`; tidak dieksekusi sesuai permintaan. |

## 7. Verifikasi yang perlu Anda jalankan (lokal)

```bash
composer install
php -l app/Http/Controllers/Admin/DashboardController.php   # + file lain yang diubah
php artisan migrate --env=testing
php artisan test
vendor/bin/phpstan analyse   # jika dipasang
composer audit
```

Cek manual: login admin (pastikan backdoor benar-benar hilang), CRUD menu + upload gambar,
buat reservasi (termasuk email kosong), halaman laporan/dashboard, notifikasi, dan rate-limit login.
