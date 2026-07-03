<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Login rate limiting
    |--------------------------------------------------------------------------
    |
    | Maksimum percobaan login gagal per (IP + email) sebelum dikunci sementara.
    | Dibaca lewat config() agar tetap benar setelah `php artisan config:cache`
    | (env() akan mengembalikan null saat config sudah di-cache).
    |
    */
    'rate_limit_login' => (int) env('RATE_LIMIT_LOGIN', 5),
];
