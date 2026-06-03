<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Proteksi route admin.
     * Cek session admin_logged_in (legacy) ATAU Laravel Auth dengan role admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isAdminSession = session('admin_logged_in') === true;

        if ($isAdminSession && !auth()->check()) {
            $email = session('admin_email', 'admin@warung.id');
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                auth()->login($user);
            }
        }

        $isAdminAuth    = auth()->check() && auth()->user()->role === 'admin';

        if (!$isAdminSession && !$isAdminAuth) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
        }

        // Pastikan akun aktif jika login via Laravel Auth
        if ($isAdminAuth && !auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
