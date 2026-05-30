<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboard;
use App\Http\Controllers\Karyawan\AttendanceController;
use App\Http\Controllers\Karyawan\LeaveController;

// ── Frontend (Publik) ─────────────────────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/reservasi', fn() => redirect('/#reservasi'))->name('reservation');
Route::get('/promo',     fn() => redirect('/#promo'))->name('promo');
Route::get('/galeri',    fn() => redirect('/#galeri'))->name('gallery');
Route::get('/fasilitas', fn() => redirect('/#fasilitas'))->name('facilities');
Route::get('/tentang',   fn() => redirect('/#tentang'))->name('about');
Route::get('/kontak',    fn() => redirect('/#kontak'))->name('contact');
Route::get('/checkout',  [PageController::class, 'checkout'])->name('checkout');

// ── Auth Multi-Role ───────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login',   [AuthController::class, 'loginPost'])
        ->name('login.post')
        ->middleware('throttle.login');
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
    Route::post('/register',[AuthController::class, 'registerPost'])
        ->name('register.post')
        ->middleware('throttle:10,1'); // maks 10 register per menit per IP
});

Route::middleware('auth')->group(function () {
    Route::post('/logout',          [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile',          [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update',  [AuthController::class, 'profileUpdate'])->name('profile.update');

    // ── Notifikasi (shared admin & karyawan) ─────────────────────────────
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

    // ── MANAJEMEN KARYAWAN ────────────────────────────────────────────────
    Route::get('/employees',                       [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create',                [EmployeeController::class, 'create'])->name('employees.create')->middleware('permission:edit_employee');
    Route::post('/employees',                      [EmployeeController::class, 'store'])->name('employees.store')->middleware('permission:edit_employee');
    Route::get('/employees/{employee}',            [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit',       [EmployeeController::class, 'edit'])->name('employees.edit')->middleware('permission:edit_employee');
    Route::put('/employees/{employee}',            [EmployeeController::class, 'update'])->name('employees.update')->middleware('permission:edit_employee');
    Route::delete('/employees/{employee}',         [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('permission:delete_data');

    // Absensi (Admin) and Pengajuan Cuti routes removed per request
    /*
    // Absensi (Admin view)
    Route::get('/attendance',                      [EmployeeController::class, 'attendanceIndex'])->name('attendance.index');

    // Pengajuan Cuti (Admin)
    Route::get('/leaves',                          [EmployeeController::class, 'leaveIndex'])->name('leaves.index');
    Route::post('/leaves/{leave}/approve',         [EmployeeController::class, 'leaveApprove'])->name('leaves.approve')->middleware('permission:manage_leaves');
    */

    // Activity Log
    Route::get('/activity-log',    [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log')->middleware('permission:view_activity_log');
    Route::delete('/activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('activity-log.clear')->middleware('permission:clear_activity_log');
});

// Karyawan routes disabled per request.
// If you want to re-enable the employee dashboard later, restore the routes below.
/*
Route::prefix('karyawan')->name('karyawan.')->middleware(['auth', 'role:karyawan'])->group(function () {

    Route::get('/',         [KaryawanDashboard::class, 'index'])->name('dashboard');
    Route::get('/jadwal',   [KaryawanDashboard::class, 'schedule'])->name('schedule');

    // Absensi
    Route::get('/absensi',              [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/absensi/checkin',     [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/absensi/checkout',    [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::post('/absensi/qr',          [AttendanceController::class, 'qrScan'])->name('attendance.qr');
    // Form web (non-AJAX) untuk tombol utama
    Route::post('/absensi/checkin-web', [AttendanceController::class, 'checkInWeb'])->name('attendance.checkin.web');
    Route::post('/absensi/checkout-web',[AttendanceController::class, 'checkOutWeb'])->name('attendance.checkout.web');

    // Pengajuan Cuti
    Route::get('/cuti',             [LeaveController::class, 'index'])->name('leave');
    Route::post('/cuti',            [LeaveController::class, 'store'])->name('leave.store');
    Route::delete('/cuti/{leaveRequest}', [LeaveController::class, 'destroy'])->name('leave.destroy');
});
*/
