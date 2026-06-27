<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Cek apakah user yang login memiliki role yang diizinkan.
     * Contoh penggunaan di route: middleware('role:admin,karyawan')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        if (!in_array($user->role, $roles)) {
            // Super Admin selalu diizinkan ke semua route admin
            if ($user->role === 'super_admin') {
                return $next($request);
            }

            // Redirect ke halaman yang sesuai dengan role mereka
            return match($user->role) {
                'admin'    => redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.'),
                'karyawan' => redirect()->route('karyawan.dashboard')->with('error', 'Akses ditolak.'),
                default    => redirect()->route('home')->with('error', 'Akses ditolak.'),
            };
        }

        return $next($request);
    }
}
