<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // ── Auth (Legacy session-based) ───────────────────────────────────────

    public function loginPage()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $validEmail    = 'admin@warung.id';
        $validPassword = 'admin123';

        if ($request->email === $validEmail && $request->password === $validPassword) {
            $request->session()->regenerate();
            session([
                'admin_logged_in' => true,
                'admin_name'      => 'Administrator',
                'admin_email'     => $validEmail,
            ]);
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        // Logout dari Auth Laravel (untuk user yang login via Auth::login)
        Auth::logout();

        // Hapus session admin legacy
        $request->session()->forget(['admin_logged_in', 'admin_name', 'admin_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Berhasil logout.');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────

    public function index()
    {
        // ── KPI Stats ────────────────────────────────────────────────────
        $stats = [
            'total_pelanggan'       => User::where('role', 'pelanggan')->count(),
            'pelanggan_bulan_ini'   => User::where('role', 'pelanggan')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total_karyawan'        => Employee::count(),
            'karyawan_aktif'        => Employee::where('status', 'aktif')->count(),
            'hadir_hari_ini'        => Attendance::whereDate('date', today())->whereIn('status', ['hadir', 'terlambat'])->count(),
            'terlambat_hari_ini'    => Attendance::whereDate('date', today())->where('status', 'terlambat')->count(),
            'cuti_menunggu'         => LeaveRequest::where('status', 'menunggu')->count(),
            'total_menu_aktif'      => Menu::where('is_active', true)->count(),
            'menu_habis'            => Menu::where('is_stock', false)->where('is_active', true)->count(),
            'total_transaksi'       => Menu::sum('sold_count'),
            'promo_aktif'           => Promo::where('is_active', true)->count(),
        ];

        // ── Grafik Penjualan 7 Hari (berdasarkan sold_count per kategori) ─
        // Karena tidak ada tabel orders, kita simulasikan dari sold_count menu
        // dengan distribusi per hari menggunakan seed dari data nyata
        $totalSold = max($stats['total_transaksi'], 1);
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            // Distribusi realistis: weekend lebih tinggi
            $dayOfWeek = $date->dayOfWeek; // 0=Sun, 6=Sat
            $multiplier = in_array($dayOfWeek, [0, 5, 6]) ? 1.4 : 1.0;
            $base = round(($totalSold / 30) * $multiplier);
            $chartData[] = [
                'day'   => $date->translatedFormat('D'),
                'date'  => $date->format('d/m'),
                'value' => max($base, 0),
                'rev'   => 'Rp ' . number_format($base * 35000, 0, ',', '.'), // estimasi avg Rp35k/item
            ];
        }
        $maxChart = (int) max(max(array_column($chartData, 'value')), 1);

        // ── Top Menu (real dari sold_count) ──────────────────────────────
        $topMenus = Menu::where('is_active', true)
            ->orderBy('sold_count', 'desc')
            ->take(5)
            ->get();
        $maxSold = $topMenus->max('sold_count') ?: 1;

        // ── Absensi breakdown hari ini ────────────────────────────────────
        $attendanceToday = Attendance::whereDate('date', today())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ── Karyawan terbaru ──────────────────────────────────────────────
        $latestEmployees = Employee::with('user')->latest()->take(4)->get();

        // ── Distribusi menu per kategori ──────────────────────────────────
        $menuByCategory = Menu::where('is_active', true)
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // ── Cuti pending terbaru ──────────────────────────────────────────
        $pendingLeaves = LeaveRequest::with('employee.user')
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'chartData', 'maxChart',
            'topMenus', 'maxSold',
            'attendanceToday', 'latestEmployees',
            'menuByCategory', 'pendingLeaves'
        ));
    }

    // ── Pages ─────────────────────────────────────────────────────────────

    public function orders()       { return view('admin.orders'); }
    public function customers()    { return view('admin.customers'); }
    public function reservations() {
        if (!Schema::hasTable('reservations')) {
            return view('admin.reservations')->with('reservations', collect());
        }

        $reservations = Reservation::with('user')
            ->orderBy('reservation_date', 'desc')
            ->paginate(15);
        
        return view('admin.reservations', compact('reservations'));
    }
    public function reports(Request $request)
    {
        $tab   = $request->get('tab', 'penjualan');
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);
        $range = $request->get('range', 'bulanan'); // harian|mingguan|bulanan

        // ── A. LAPORAN PENJUALAN ──────────────────────────────────────────
        // Harian: 7 hari terakhir
        $salesHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dow  = $date->dayOfWeek;
            $mult = in_array($dow, [0, 5, 6]) ? 1.4 : 1.0;
            $base = (int) round((Menu::sum('sold_count') / 30) * $mult);
            $salesHarian[] = [
                'label' => $date->translatedFormat('D, d M'),
                'short' => $date->translatedFormat('D'),
                'date'  => $date->format('d/m'),
                'items' => $base,
                'rev'   => $base * 35000,
            ];
        }

        // Mingguan: 4 minggu terakhir
        $salesMingguan = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->startOfWeek()->subWeeks($i);
            $end   = $start->copy()->endOfWeek();
            $days  = 7;
            $base  = (int) round((Menu::sum('sold_count') / 4) * (1 + ($i === 0 ? 0.1 : 0)));
            $salesMingguan[] = [
                'label' => $start->format('d M') . ' – ' . $end->format('d M'),
                'short' => 'Minggu ' . (4 - $i),
                'items' => $base,
                'rev'   => $base * 35000,
            ];
        }

        // Bulanan: 6 bulan terakhir
        $salesBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $base  = (int) round(Menu::sum('sold_count') / 6);
            $salesBulanan[] = [
                'label' => $date->translatedFormat('F Y'),
                'short' => $date->translatedFormat('M'),
                'items' => $base,
                'rev'   => $base * 35000,
            ];
        }

        // Top menu terlaris
        $topMenus = Menu::where('is_active', true)
            ->orderBy('sold_count', 'desc')
            ->take(10)
            ->get();

        // Ringkasan penjualan
        $totalItems   = Menu::sum('sold_count');
        $totalRevEst  = $totalItems * 35000;
        $avgRating    = Menu::where('is_active', true)->avg('rating') ?? 0;
        $menuHabis    = Menu::where('is_stock', false)->where('is_active', true)->count();

        // ── B. LAPORAN RESERVASI ──────────────────────────────────────────
        if (!Schema::hasTable('reservations')) {
            $reservations = collect();
            $reservasiStats = [
                'total'      => 0,
                'konfirmasi' => 0,
                'menunggu'   => 0,
                'batal'      => 0,
                'selesai'    => 0,
            ];
        } else {
            $reservations = Reservation::whereMonth('reservation_date', $month)
                ->whereYear('reservation_date', $year)
                ->get();

            $reservasiStats = [
                'total'      => $reservations->count(),
                'konfirmasi' => $reservations->where('status', 'confirmed')->count(),
                'menunggu'   => $reservations->where('status', 'pending')->count(),
                'batal'      => $reservations->where('status', 'cancelled')->count(),
                'selesai'    => $reservations->where('status', 'completed')->count(),
            ];
        }

        $reservasiHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Schema::hasTable('reservations')
                ? Reservation::whereDate('reservation_date', $date)->count()
                : 0;
            $reservasiHarian[] = [
                'short' => $date->translatedFormat('D'),
                'date'  => $date->format('d/m'),
                'total' => $count,
            ];
        }

        // ── C. LAPORAN ABSENSI ────────────────────────────────────────────
        $attendances = Attendance::with('employee.user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $absensiStats = [
            'hadir'     => $attendances->whereIn('status', ['hadir','terlambat'])->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin'      => $attendances->where('status', 'izin')->count(),
            'sakit'     => $attendances->where('status', 'sakit')->count(),
            'alpha'     => $attendances->where('status', 'alpha')->count(),
            'avg_late'  => round($attendances->where('status', 'terlambat')->avg('late_minutes') ?? 0),
        ];

        $employees = Employee::with('user')->where('status', 'aktif')->get();

        // Per karyawan
        $absensiPerKaryawan = $employees->map(function ($emp) use ($attendances) {
            $empAtt = $attendances->where('employee_id', $emp->id);
            return [
                'employee' => $emp,
                'hadir'    => $empAtt->whereIn('status', ['hadir','terlambat'])->count(),
                'terlambat'=> $empAtt->where('status', 'terlambat')->count(),
                'izin'     => $empAtt->where('status', 'izin')->count(),
                'sakit'    => $empAtt->where('status', 'sakit')->count(),
                'alpha'    => $empAtt->where('status', 'alpha')->count(),
                'avg_late' => round($empAtt->where('status', 'terlambat')->avg('late_minutes') ?? 0),
            ];
        })->sortByDesc('hadir')->values();

        // Trend absensi 7 hari terakhir
        $absensiTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = Carbon::now()->subDays($i);
            $dayAtt = Attendance::whereDate('date', $date)->get();
            $absensiTrend[] = [
                'short'     => $date->translatedFormat('D'),
                'date'      => $date->format('d/m'),
                'hadir'     => $dayAtt->whereIn('status', ['hadir','terlambat'])->count(),
                'terlambat' => $dayAtt->where('status', 'terlambat')->count(),
                'alpha'     => Employee::where('status','aktif')->count() - $dayAtt->count(),
            ];
        }

        // ── D. LAPORAN CUTI ───────────────────────────────────────────────
        $leaves = LeaveRequest::with('employee.user')
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->get();

        $cutiStats = [
            'total'     => $leaves->count(),
            'disetujui' => $leaves->where('status', 'disetujui')->count(),
            'menunggu'  => $leaves->where('status', 'menunggu')->count(),
            'ditolak'   => $leaves->where('status', 'ditolak')->count(),
            'total_hari'=> $leaves->where('status', 'disetujui')->sum('total_days'),
        ];

        $cutiByType = [
            'cuti_tahunan' => $leaves->where('type', 'cuti_tahunan')->count(),
            'sakit'        => $leaves->where('type', 'sakit')->count(),
            'izin'         => $leaves->where('type', 'izin')->count(),
            'cuti_khusus'  => $leaves->where('type', 'cuti_khusus')->count(),
        ];

        return view('admin.reports', compact(
            'tab', 'month', 'year', 'range',
            // Penjualan
            'salesHarian', 'salesMingguan', 'salesBulanan',
            'topMenus', 'totalItems', 'totalRevEst', 'avgRating', 'menuHabis',
            // Reservasi
            'reservasiStats', 'reservasiHarian',
            // Absensi
            'attendances', 'absensiStats', 'absensiPerKaryawan', 'absensiTrend', 'employees',
            // Cuti
            'leaves', 'cutiStats', 'cutiByType'
        ));
    }
    public function settings()     { return view('admin.settings'); }
}
