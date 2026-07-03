<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogin
{
    public function __construct(protected RateLimiter $limiter) {}

    /**
     * Batasi percobaan login: maks 5x per menit per IP+email.
     * Setelah 5x gagal, kunci 60 detik.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'login:' . sha1($request->ip() . '|' . strtolower($request->input('email', '')));
        $maxAttempts = (int) config('security.rate_limit_login', 5);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
                ]);
        }

        $response = $next($request);

        // Jika login gagal (redirect back dengan errors), tambah counter
        if ($response->isRedirect() && session()->has('errors')) {
            $this->limiter->hit($key, 60); // kunci 60 detik
        } else {
            // Login berhasil — reset counter
            $this->limiter->clear($key);
        }

        return $response;
    }
}
