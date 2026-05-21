<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Tambahkan security headers ke setiap response.
     * Mencegah XSS, clickjacking, MIME sniffing, dll.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya tambahkan ke HTML responses
        if (!$response instanceof \Illuminate\Http\Response) {
            return $response;
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(self), geolocation=(self), microphone=()');

        // Content Security Policy — izinkan CDN yang dipakai (Unsplash, QR API, Tailwind CDN)
        $scriptSrc = ["'self'", "'unsafe-inline'", "'unsafe-eval'"];
        $styleSrc = ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com'];
        $connectSrc = ["'self'"];

        if (app()->environment('local')) {
            $scriptSrc[] = 'http://127.0.0.1:5173';
            $scriptSrc[] = 'http://localhost:5173';
            $styleSrc[] = 'http://127.0.0.1:5173';
            $styleSrc[] = 'http://localhost:5173';
            $connectSrc[] = 'http://127.0.0.1:5173';
            $connectSrc[] = 'http://localhost:5173';
            $connectSrc[] = 'ws://127.0.0.1:5173';
            $connectSrc[] = 'ws://localhost:5173';
        }

        $csp = implode('; ', [
            "default-src 'self'",
            'script-src ' . implode(' ', $scriptSrc),
            'style-src ' . implode(' ', $styleSrc),
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: blob: https: http:",
            'connect-src ' . implode(' ', $connectSrc),
            "frame-ancestors 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // Hapus header yang membocorkan info server
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
