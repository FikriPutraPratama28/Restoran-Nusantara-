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

            if (Auth::check()) {
                $userId = Auth::id();
                $unreadCount = Notification::forUser($userId)->unread()->count();
                $latestNotifs = Notification::forUser($userId)
                    ->latest()
                    ->take(8)
                    ->get();
                $view->with('_unreadCount', $unreadCount);
                $view->with('_latestNotifs', $latestNotifs);
            } else {
                $view->with('_unreadCount', 0);
                $view->with('_latestNotifs', collect());
            }
        });
    }
}
