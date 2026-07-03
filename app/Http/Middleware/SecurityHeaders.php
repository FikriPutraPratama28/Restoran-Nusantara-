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
            // Baca port Vite dari file public/hot (dynamic)
            $viteHosts = [
                'http://127.0.0.1:5173',
                'http://localhost:5173',
                'http://127.0.0.1:5174',
                'http://localhost:5174',
                'http://127.0.0.1:5175',
                'http://localhost:5175',
            ];
            $hotFile = public_path('hot');
            if (file_exists($hotFile)) {
                $hotUrl = rtrim(trim(file_get_contents($hotFile)), '/');
                if (!in_array($hotUrl, $viteHosts)) {
                    $viteHosts[] = $hotUrl;
                }
            }
            foreach ($viteHosts as $host) {
                $scriptSrc[] = $host;
                $styleSrc[] = $host;
                $connectSrc[] = $host;
            }
            // WebSocket for HMR
            $wsHosts = [
                'ws://127.0.0.1:5173',
                'ws://localhost:5173',
                'ws://127.0.0.1:5174',
                'ws://localhost:5174',
                'ws://127.0.0.1:5175',
                'ws://localhost:5175',
            ];
            if (file_exists($hotFile)) {
                $hotUrl = trim(file_get_contents($hotFile));
                $wsUrl = str_replace(['http://', 'https://'], 'ws://', rtrim($hotUrl, '/'));
                if (!in_array($wsUrl, $wsHosts)) {
                    $wsHosts[] = $wsUrl;
                }
            }
            foreach ($wsHosts as $host) {
                $connectSrc[] = $host;
            }
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
