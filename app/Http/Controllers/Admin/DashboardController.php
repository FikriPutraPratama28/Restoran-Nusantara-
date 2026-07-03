<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Reservation;
use App\Models\ActivityLog;
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
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!in_array($user->role, ['admin', 'super_admin'])) {
                Auth::logout();
                return back()
                    ->withErrors(['email' => 'Akses ditolak. Anda bukan administrator.'])
                    ->withInput($request->only('email'));
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()
                    ->withErrors(['email' => 'Akun Anda telah dinonaktifkan.'])
                    ->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            session([
                'admin_logged_in' => true,
                'admin_name'      => $user->name,
                'admin_email'     => $user->email,
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
        $activeReservations = Reservation::where('status', '!=', 'cancelled')->get();
        $totalRevenue = $activeReservations->sum(function($r) { return $r->total_price; });

        // ── KPI Stats ────────────────────────────────────────────────────
        $stats = [
            'total_pelanggan'       => User::where('role', 'pelanggan')->count(),
            'pelanggan_bulan_ini'   => User::where('role', 'pelanggan')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total_menu_aktif'      => Menu::where('is_active', true)->count(),
            'menu_habis'            => Menu::where('is_stock', false)->where('is_active', true)->count(),
            'total_transaksi'       => $activeReservations->count(),
            'total_pendapatan'      => $totalRevenue,
            'promo_aktif'           => Promo::where('is_active', true)->count(),
        ];

        // ── Grafik Penjualan 7 Hari berdasarkan riil data ────────────────
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $dateStr = $dateObj->toDateString();
            
            $dayReservations = Reservation::whereDate('reservation_date', $dateStr)
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $tally = $this->tallyOrderedItems($dayReservations);
            $itemsCount = $tally['items'];
            $revenue = $tally['rev'];
            
            $chartData[] = [
                'day'   => $dateObj->translatedFormat('D'),
                'date'  => $dateObj->format('d/m'),
                'value' => $itemsCount,
                'rev_num'=> $revenue,
                'rev'   => 'Rp ' . number_format($revenue, 0, ',', '.'),
            ];
        }
        $maxChart = (int) max(max(array_column($chartData, 'value')), 1);

        // ── Top Menu (dinamis berdasarkan order) ─────────────────────────
        $menuCounts = $this->tallyMenuCounts($activeReservations);
        arsort($menuCounts);
        $topMenuIds = array_keys(array_slice($menuCounts, 0, 5, true));
        $topMenus = Menu::whereIn('id', $topMenuIds)->get();
        foreach ($topMenus as $menu) {
            $menu->sold_count = $menuCounts[$menu->id] ?? 0;
        }
        $topMenus = $topMenus->sortByDesc('sold_count');
        if ($topMenus->isEmpty()) {
            $topMenus = Menu::where('is_active', true)->orderBy('sold_count', 'desc')->take(5)->get();
        }
        $maxSold = $topMenus->max('sold_count') ?: 1;

        // ── Distribusi menu per kategori ──────────────────────────────────
        $menuByCategory = Menu::where('is_active', true)
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // ── 5 Reservasi Terbaru ──────────────────────────────────────────
        $recentReservations = Reservation::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'chartData', 'maxChart',
            'topMenus', 'maxSold',
            'menuByCategory', 'recentReservations'
        ));
    }

    // ── Pages ─────────────────────────────────────────────────────────────

    public function orders()       { return view('admin.orders'); }
    public function customers()    { return view('admin.customers'); }
    public function reservations() {
        if (!Schema::hasTable('reservations')) {
            $stats = [
                'today' => 0,
                'tomorrow' => 0,
                'week' => 0,
            ];

            return view('admin.reservations')
                ->with('reservations', collect())
                ->with('stats', $stats);
        }

        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();

        $stats = [
            'today' => Reservation::whereDate('reservation_date', $today)->count(),
            'tomorrow' => Reservation::whereDate('reservation_date', $tomorrow)->count(),
            'week' => Reservation::whereBetween('reservation_date', [$weekStart, $weekEnd])->count(),
        ];

        $reservations = Reservation::with('user')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(15);
        
        return view('admin.reservations', compact('reservations', 'stats'));
    }

    /**
     * Update reservation status from admin area
     */
    public function updateReservationStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $oldStatus = $reservation->status;
        $reservation->update(['status' => $validated['status']]);

        // Log activity
        ActivityLog::log(
            'update_reservation_status',
            'Reservation',
            "Mengubah status reservasi " . ($reservation->reservation_code ?? '#RES-' . $reservation->id) . " dari {$oldStatus} menjadi {$validated['status']}",
            $reservation
        );

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui menjadi ' . ucfirst($validated['status']));
    }

    public function reports(Request $request)
    {
        $tab   = $request->get('tab', 'penjualan');
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);
        $range = $request->get('range', 'bulanan'); // harian|mingguan|bulanan

        // ── A. LAPORAN PENJUALAN (Real Data) ──────────────────────────────
        // Harian: 7 hari terakhir
        $salesHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $dateStr = $dateObj->toDateString();
            $dayReservations = Reservation::whereDate('reservation_date', $dateStr)
                ->where('status', '!=', 'cancelled')
                ->get();
            $tally = $this->tallyOrderedItems($dayReservations);
            $itemsCount = $tally['items'];
            $revenue = $tally['rev'];
            $salesHarian[] = [
                'label' => $dateObj->translatedFormat('D, d M'),
                'short' => $dateObj->translatedFormat('D'),
                'date'  => $dateObj->format('d/m'),
                'items' => $itemsCount,
                'rev'   => $revenue,
            ];
        }

        // Mingguan: 4 minggu terakhir
        $salesMingguan = [];
        for ($i = 3; $i >= 0; $i--) {
            $startObj = Carbon::now()->startOfWeek()->subWeeks($i);
            $endObj   = $startObj->copy()->endOfWeek();
            $weekReservations = Reservation::whereBetween('reservation_date', [$startObj->toDateString(), $endObj->toDateString()])
                ->where('status', '!=', 'cancelled')
                ->get();
            $tally = $this->tallyOrderedItems($weekReservations);
            $itemsCount = $tally['items'];
            $revenue = $tally['rev'];
            $salesMingguan[] = [
                'label' => $startObj->format('d M') . ' – ' . $endObj->format('d M'),
                'short' => 'Minggu ' . (4 - $i),
                'items' => $itemsCount,
                'rev'   => $revenue,
            ];
        }

        // Bulanan: 6 bulan terakhir
        $salesBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subMonths($i);
            $monthReservations = Reservation::whereMonth('reservation_date', $dateObj->month)
                ->whereYear('reservation_date', $dateObj->year)
                ->where('status', '!=', 'cancelled')
                ->get();
            $tally = $this->tallyOrderedItems($monthReservations);
            $itemsCount = $tally['items'];
            $revenue = $tally['rev'];
            $salesBulanan[] = [
                'label' => $dateObj->translatedFormat('F Y'),
                'short' => $dateObj->translatedFormat('M'),
                'items' => $itemsCount,
                'rev'   => $revenue,
            ];
        }

        // Top menu terlaris (dinamis berdasarkan order)
        $allReservations = Reservation::where('status', '!=', 'cancelled')->get();
        $menuCounts = $this->tallyMenuCounts($allReservations);
        arsort($menuCounts);
        $topMenuIds = array_keys(array_slice($menuCounts, 0, 10, true));
        $topMenus = Menu::whereIn('id', $topMenuIds)->get();
        foreach ($topMenus as $menu) {
            $menu->sold_count = $menuCounts[$menu->id] ?? 0;
        }
        $topMenus = $topMenus->sortByDesc('sold_count');
        if ($topMenus->isEmpty()) {
            $topMenus = Menu::where('is_active', true)->orderBy('sold_count', 'desc')->take(10)->get();
        }

        // Ringkasan penjualan riil
        $summary = $this->tallyOrderedItems($allReservations);
        $totalItems  = $summary['items'];
        $totalRevEst = $summary['rev'];
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
            $dateObj = Carbon::now()->subDays($i);
            $count = Schema::hasTable('reservations')
                ? Reservation::whereDate('reservation_date', $dateObj->toDateString())->count()
                : 0;
            $reservasiHarian[] = [
                'short' => $dateObj->translatedFormat('D'),
                'date'  => $dateObj->format('d/m'),
                'total' => $count,
            ];
        }

        return view('admin.reports', compact(
            'tab', 'month', 'year', 'range',
            // Penjualan
            'salesHarian', 'salesMingguan', 'salesBulanan',
            'topMenus', 'totalItems', 'totalRevEst', 'avgRating', 'menuHabis',
            // Reservasi
            'reservasiStats', 'reservasiHarian'
        ));
    }
    public function settings()     { return view('admin.settings'); }

    // ── Helpers ──────────────────────────────────────────

    /**
     * Hitung total item terjual & estimasi pendapatan dari koleksi reservasi.
     *
     * @return array{items:int, rev:int}
     */
    private function tallyOrderedItems($reservations): array
    {
        $items = 0;
        $rev   = 0;
        foreach ($reservations as $r) {
            if (is_array($r->ordered_items)) {
                foreach ($r->ordered_items as $item) {
                    $qty = $item['qty'] ?? 0;
                    $items += $qty;
                    $rev   += ($item['price'] ?? 0) * $qty;
                }
            }
        }
        return ['items' => $items, 'rev' => $rev];
    }

    /**
     * Akumulasi jumlah qty per menu id dari koleksi reservasi.
     *
     * @return array<int|string, int>
     */
    private function tallyMenuCounts($reservations): array
    {
        $counts = [];
        foreach ($reservations as $res) {
            if (is_array($res->ordered_items)) {
                foreach ($res->ordered_items as $item) {
                    if (!isset($item['id'])) {
                        continue;
                    }
                    $counts[$item['id']] = ($counts[$item['id']] ?? 0) + ($item['qty'] ?? 0);
                }
            }
        }
        return $counts;
    }
}
