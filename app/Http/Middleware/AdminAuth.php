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
        // Pulihkan Laravel Auth dari session admin legacy HANYA jika user valid, ber-role admin, & aktif.
        if (!auth()->check() && session('admin_logged_in') === true && session('admin_email')) {
            $user = \App\Models\User::where('email', session('admin_email'))->first();
            if ($user && in_array($user->role, ['admin', 'super_admin']) && $user->is_active) {
                auth()->login($user);
            } else {
                // Session legacy tidak valid / bukan admin — bersihkan agar tidak menembus otorisasi.
                session()->forget(['admin_logged_in', 'admin_name', 'admin_email']);
            }
        }

        $isAdminAuth = auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']);

        if (!$isAdminAuth) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
        }

        // Pastikan akun aktif
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
