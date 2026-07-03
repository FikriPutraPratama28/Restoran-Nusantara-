# Rencana Upgrade Laravel (rekomendasi)

> Tidak dieksekusi pada audit ini sesuai permintaan. Kerjakan di branch terpisah + jalankan test tiap langkah.

## ⚠️ Status: SECURITY-DRIVEN (bukan sekadar housekeeping)

`composer audit` (3 Jul 2026) menemukan **3 advisory** pada `laravel/framework` versi 10.x yang dipakai project ini:

| Severity | Judul | Advisory | Versi aman |
|---|---|---|---|
| High | CRLF injection di aturan validasi `email` default | GHSA-5vg9-5847-vvmq / CVE-2026-48019 | >= 12.60.0 |
| Medium | Temporary Signed URL Path Confusion | GHSA-crmm-hgp2-wgrp | >= 12.61.1 |

**Penting:** rentang "affected" mencakup **seluruh 10.x dan 11.x tanpa versi patch** — karena Laravel 10 dan 11 sudah EOL untuk security fix. Satu-satunya remediasi nyata adalah **upgrade ke Laravel `^12.61.1`** (menutup ketiga advisory sekaligus). Yang High relevan langsung karena aturan `email` dipakai di login & form reservasi.

## Kondisi saat ini

- `laravel/framework: ^10.0`, PHP `^8.1`.
- Laravel 10 EOL: tidak menerima lagi bug fix maupun security fix. **Target minimal aman: `^12.61.1`.**

## Prasyarat

1. PHP: Laravel 11/12 butuh **PHP >= 8.2**. Pastikan server & CI memakai PHP 8.2/8.3.
2. Pastikan test suite berjalan. `APP_KEY` untuk test sudah ditambahkan di `phpunit.xml`; untuk runtime jalankan `php artisan key:generate`. Disarankan tambah smoke test (auth admin, menu CRUD, reservasi) sebelum upgrade.
3. Commit/branch bersih.

## Langkah upgrade 10 -> 11

1. Naikkan constraint di `composer.json`:
   - `php` -> `^8.2`
   - `laravel/framework` -> `^11.0`
   - `laravel/sanctum` -> `^4.0`
   - `nunomaduro/collision` -> `^8.0`
   - `phpunit/phpunit` -> `^11.0`
   - `spatie/laravel-ignition` -> **hapus** (Ignition sudah bawaan di L11).
2. `composer update` lalu perbaiki error.
3. Struktur L11 baru bersifat opsional. Project ini masih pakai struktur L10 klasik (`app/Http/Kernel.php`, `app/Console/Kernel.php`, `app/Exceptions/Handler.php`) yang tetap valid — **tidak wajib** migrasi ke `bootstrap/app.php` gaya baru. Alias middleware (`admin`, `role`, `permission`, `throttle.login`) & global middleware `SecurityHeaders` tetap lewat `Kernel.php`.
4. Cek deprecations. Pertimbangkan aktifkan `Model::preventLazyLoading()` di non-production untuk mendeteksi N+1.
5. `php artisan test`.

## Langkah upgrade 11 -> 12 (>= 12.61.1)

1. `laravel/framework` -> `^12.61.1`, sesuaikan paket dev (phpunit `^11/^12`, collision).
2. `composer update` + `composer audit` (harus bersih) + `php artisan test`.

## Setelah upgrade

- Jalankan `php artisan config:cache route:cache view:cache`. Pemakaian `env()` di luar config sudah diperbaiki pada audit ini (`ThrottleLogin` -> `config('security.rate_limit_login')`).
- Verifikasi manual: login admin (pastikan backdoor benar-benar hilang), CRUD menu (+upload gambar), buat reservasi (termasuk email kosong), halaman laporan/dashboard, notifikasi, rate-limit login.

## Frontend

- Vite 4 + Tailwind 3 masih kompatibel. Opsional naik Vite 5 & Tailwind 3.4; tidak wajib untuk upgrade Laravel.

## Alternatif jika upgrade tertunda

Upgrade adalah satu-satunya fix resmi. Sebagai mitigasi sementara untuk advisory High (CRLF di rule `email`): tambahkan sanitasi input email (tolak karakter `\r`/`\n`) sebelum validasi, mis. `str_replace(["\r", "\n"], '', $email)` atau regex tambahan. Ini **hanya penambal**, bukan pengganti upgrade.
