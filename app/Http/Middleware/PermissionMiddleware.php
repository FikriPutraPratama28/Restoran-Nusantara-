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
        // Wajib terautentikasi. AdminAuth sudah memulihkan Auth dari session admin legacy bila valid,
        // sehingga jalur "session-only = akses penuh" (bypass permission) dihapus.
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
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
