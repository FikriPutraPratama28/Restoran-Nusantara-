<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\SiteSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set Carbon locale ke Bahasa Indonesia
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian');

        // Gunakan Tailwind pagination views
        Paginator::useTailwind();

        // Share notifikasi ke semua view (hanya jika user login)
        View::composer('*', function ($view) {
            // Site settings (branding) — selalu tersedia
            try {
                $siteSettings = SiteSetting::allCached();
            } catch (\Throwable $e) {
                $siteSettings = [];
            }
            $view->with('_site', $siteSettings);

            $userId = null;
            if (Auth::check()) {
                $userId = Auth::id();
            } elseif (session('admin_logged_in') && session('admin_email')) {
                try {
                    $adminUser = \App\Models\User::where('email', session('admin_email'))->first();
                    if ($adminUser) {
                        $userId = $adminUser->id;
                    }
                } catch (\Throwable $e) {
                    $userId = null;
                }
            }

            if ($userId !== null) {
                try {
                    $unreadCount = Notification::forUser($userId)->unread()->count();
                    $latestNotifs = Notification::forUser($userId)
                        ->latest()
                        ->take(8)
                        ->get();
                } catch (\Throwable $e) {
                    $unreadCount = 0;
                    $latestNotifs = collect();
                }
                $view->with('_unreadCount', $unreadCount);
                $view->with('_latestNotifs', $latestNotifs);
            } else {
                $view->with('_unreadCount', 0);
                $view->with('_latestNotifs', collect());
            }
        });
    }
}
