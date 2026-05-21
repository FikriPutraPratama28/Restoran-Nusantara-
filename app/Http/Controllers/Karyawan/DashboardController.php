<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\JadwalKerja;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return view('karyawan.no-employee');
        }

        $todayAttend = $employee->todayAttendance();

        // Statistik bulan ini
        $monthAttend = $employee->monthAttendances();
        $stats = [
            'hadir'     => $monthAttend->whereIn('status', ['hadir', 'terlambat'])->count(),
            'terlambat' => $monthAttend->where('status', 'terlambat')->count(),
            'izin'      => $monthAttend->where('status', 'izin')->count(),
            'sakit'     => $monthAttend->where('status', 'sakit')->count(),
        ];

        // Riwayat absensi 7 hari terakhir
        $recentAttend = $employee->attendances()
            ->where('date', '>=', now()->subDays(7)->toDateString())
            ->orderBy('date', 'desc')
            ->get();

        // Pengajuan cuti terbaru
        $leaveRequests = $employee->leaveRequests()
            ->latest()
            ->take(5)
            ->get();

        // Sisa cuti tahunan
        $leaveQuota  = 12;
        $usedLeave   = $employee->leaveRequests()
            ->where('type', 'cuti_tahunan')
            ->where('status', 'disetujui')
            ->whereYear('start_date', now()->year)
            ->sum('total_days');
        $remainLeave = max(0, $leaveQuota - $usedLeave);

        // Jadwal kerja mingguan
        $jadwalKerja = JadwalKerja::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderByRaw("FIELD(hari,'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->get();

        return view('karyawan.dashboard', compact(
            'user', 'employee', 'todayAttend', 'stats', 'recentAttend',
            'leaveRequests', 'remainLeave', 'jadwalKerja'
        ));
    }

    // ── Jadwal ───────────────────────────────────────────────────────────

    public function schedule()
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('karyawan.dashboard');
        }

        $month = (int) request('month', now()->month);
        $year  = (int) request('year', now()->year);

        // Validasi range bulan/tahun
        $month = max(1, min(12, $month));
        $year  = max(2020, min(now()->year + 1, $year));

        $attendances = $employee->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get()
            ->keyBy(fn($a) => $a->date->format('Y-m-d'));

        // Buat kalender bulan ini
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();
        $calendar     = [];

        for ($day = $startOfMonth->copy(); $day->lte($endOfMonth); $day->addDay()) {
            $key = $day->format('Y-m-d');
            $calendar[] = [
                'date'       => $day->copy(),
                'attendance' => $attendances->get($key),
                'isToday'    => $day->isToday(),
                'isWeekend'  => $day->isWeekend(),
            ];
        }

        // Statistik bulan ini
        $monthStats = [
            'hadir'     => $attendances->whereIn('status', ['hadir', 'terlambat'])->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin'      => $attendances->where('status', 'izin')->count(),
            'sakit'     => $attendances->where('status', 'sakit')->count(),
            'alpha'     => $attendances->where('status', 'alpha')->count(),
        ];

        return view('karyawan.schedule', compact(
            'employee', 'calendar', 'month', 'year', 'attendances', 'monthStats'
        ));
    }
}
