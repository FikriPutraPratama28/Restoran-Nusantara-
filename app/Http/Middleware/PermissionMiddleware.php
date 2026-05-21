<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Cek apakah user memiliki permission tertentu.
     *
     * Contoh penggunaan di route:
     *   ->middleware('permission:delete_data')
     *   ->middleware('permission:edit_menu,edit_content')
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        // Cek login via Laravel Auth ATAU legacy admin session
        $isAdminSession = session('admin_logged_in') === true;
        $isAuthUser     = Auth::check();

        if (!$isAuthUser && !$isAdminSession) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Legacy admin session = akses penuh (admin role)
        if ($isAdminSession && !$isAuthUser) {
            return $next($request);
        }

        $user = Auth::user();

        foreach ($permissions as $permission) {
            if (!$user->hasPermission($permission)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.',
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.');
            }
        }

        return $next($request);
    }
}
