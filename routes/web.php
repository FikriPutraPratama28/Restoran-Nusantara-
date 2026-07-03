<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReservationController;

// ── Frontend (Publik) ─────────────────────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/reservasi', [PageController::class, 'reservation'])->name('reservation');
Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/reservasi/{code}', [ReservationController::class, 'receipt'])->name('reservation.receipt');
Route::get('/promo',     [PageController::class, 'promo'])->name('promo');
Route::get('/galeri',    fn() => redirect('/#galeri'))->name('gallery');
Route::get('/fasilitas', fn() => redirect('/#fasilitas'))->name('facilities');
Route::get('/tentang',   [PageController::class, 'about'])->name('about');
Route::get('/kontak',    [PageController::class, 'contact'])->name('contact');
Route::get('/checkout',  [PageController::class, 'checkout'])->name('checkout');

// ── Auth & Redirects ─────────────────────────────────────────────────────
Route::redirect('/login', '/admin/login')->name('login');

// ── Notifikasi (Admin Only) ──────────────────────────────────────────────
Route::middleware('admin')->group(function () {
    Route::get('/notifications',                        [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read',   [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all',              [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}',      [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count',           [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

// ── Admin Auth (Legacy) ───────────────────────────────────────────────────
Route::get( '/admin/login',  [DashboardController::class, 'loginPage'])->name('admin.login');
Route::post('/admin/login',  [DashboardController::class, 'loginPost'])
    ->name('admin.login.post')
    ->middleware('throttle.login');
Route::get( '/admin/logout', [DashboardController::class, 'logout'])->name('admin.logout');

// ── Admin Pages ───────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    // Dashboard & pages
    Route::get('/',             [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders',       [DashboardController::class, 'orders'])->name('orders');
    Route::get('/customers',    [DashboardController::class, 'customers'])->name('customers');
    Route::get('/reservations', [DashboardController::class, 'reservations'])->name('reservations');
    Route::patch('/reservations/{reservation}/status', [DashboardController::class, 'updateReservationStatus'])->name('reservations.status');
    Route::get('/reports',      [DashboardController::class, 'reports'])->name('reports')->middleware('permission:view_reports');
    Route::get('/settings',     [SettingsController::class, 'index'])->name('settings')->middleware('permission:view_reports');
    Route::post('/settings/branding', [SettingsController::class, 'updateBranding'])->name('settings.branding')->middleware('permission:view_reports');
    Route::post('/settings/home',     [SettingsController::class, 'updateHome'])->name('settings.home')->middleware('permission:view_reports');

    // Menu CRUD
    Route::get('/menu',                [MenuController::class, 'index'])->name('menu');
    Route::post('/menu',               [MenuController::class, 'store'])->name('menu.store')->middleware('permission:edit_menu');
    Route::put('/menu/{menu}',         [MenuController::class, 'update'])->name('menu.update')->middleware('permission:edit_menu');
    Route::delete('/menu/{menu}',      [MenuController::class, 'destroy'])->name('menu.destroy')->middleware('permission:delete_data');
    Route::patch('/menu/{menu}/stock', [MenuController::class, 'toggleStock'])->name('menu.toggle-stock')->middleware('permission:edit_menu');

    // ── CONTENT MANAGEMENT ────────────────────────────────────────────────

    // 1. Hero / Banner
    Route::get('/content/hero',                    [ContentController::class, 'heroIndex'])->name('content.hero');
    Route::post('/content/hero',                   [ContentController::class, 'heroStore'])->name('content.hero.store')->middleware('permission:edit_content');
    Route::put('/content/hero/{heroSlide}',        [ContentController::class, 'heroUpdate'])->name('content.hero.update')->middleware('permission:edit_content');
    Route::delete('/content/hero/{heroSlide}',     [ContentController::class, 'heroDestroy'])->name('content.hero.destroy')->middleware('permission:delete_data');

    // 2. Promo & Voucher
    Route::get('/content/promo',                   [ContentController::class, 'promoIndex'])->name('content.promo');
    Route::post('/content/promo',                  [ContentController::class, 'promoStore'])->name('content.promo.store')->middleware('permission:edit_content');
    Route::put('/content/promo/{promo}',           [ContentController::class, 'promoUpdate'])->name('content.promo.update')->middleware('permission:edit_content');
    Route::delete('/content/promo/{promo}',        [ContentController::class, 'promoDestroy'])->name('content.promo.destroy')->middleware('permission:delete_data');

    // 3. About / Cerita Kami
    Route::get('/content/about',                   [ContentController::class, 'aboutIndex'])->name('content.about');
    Route::post('/content/about',                  [ContentController::class, 'aboutUpdate'])->name('content.about.update')->middleware('permission:edit_content');

    // Team (CRUD page index)
    Route::get('/content/team',                    [ContentController::class, 'teamIndex'])->name('content.team');

    // 4. Team Members
    Route::post('/content/team',                   [ContentController::class, 'teamStore'])->name('content.team.store')->middleware('permission:edit_content');
    Route::put('/content/team/{teamMember}',       [ContentController::class, 'teamUpdate'])->name('content.team.update')->middleware('permission:edit_content');
    Route::delete('/content/team/{teamMember}',    [ContentController::class, 'teamDestroy'])->name('content.team.destroy')->middleware('permission:delete_data');

    // 5. Facilities
    Route::get('/content/facility',               [ContentController::class, 'facilityIndex'])->name('content.facility');
    Route::post('/content/facility',              [ContentController::class, 'facilityStore'])->name('content.facility.store')->middleware('permission:edit_content');
    Route::put('/content/facility/{facility}',    [ContentController::class, 'facilityUpdate'])->name('content.facility.update')->middleware('permission:edit_content');
    Route::delete('/content/facility/{facility}', [ContentController::class, 'facilityDestroy'])->name('content.facility.destroy')->middleware('permission:delete_data');

    // 6. Gallery / Momen Bersama
    Route::get('/content/gallery',                [ContentController::class, 'galleryIndex'])->name('content.gallery');
    Route::post('/content/gallery',               [ContentController::class, 'galleryStore'])->name('content.gallery.store')->middleware('permission:edit_content');
    Route::put('/content/gallery/{galleryImage}', [ContentController::class, 'galleryUpdate'])->name('content.gallery.update')->middleware('permission:edit_content');
    Route::delete('/content/gallery/{galleryImage}', [ContentController::class, 'galleryDestroy'])->name('content.gallery.destroy')->middleware('permission:delete_data');

    // Manajemen karyawan dinonaktifkan

    // Absensi (Admin) and Pengajuan Cuti routes removed per request

    // Activity Log
    Route::get('/activity-log',    [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log')->middleware('permission:view_activity_log');
    Route::delete('/activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('activity-log.clear')->middleware('permission:clear_activity_log');

    // ── MANAJEMEN USER (Super Admin only) ─────────────────────────────────
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/users',                          [UserController::class, 'index'])->name('users');
        Route::post('/users',                         [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',                   [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',                [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle-active',   [UserController::class, 'toggleActive'])->name('users.toggle-active');
    });
});

