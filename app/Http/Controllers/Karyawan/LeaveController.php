<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('karyawan.dashboard');
        }

        $leaves = $employee->leaveRequests()
            ->latest()
            ->paginate(10);

        // Hitung sisa cuti tahunan (asumsi 12 hari/tahun)
        $usedLeave = $employee->leaveRequests()
            ->where('type', 'cuti_tahunan')
            ->where('status', 'disetujui')
            ->whereYear('start_date', now()->year)
            ->sum('total_days');

        $leaveQuota = 12;
        $remainingLeave = max(0, $leaveQuota - $usedLeave);

        return view('karyawan.leave', compact('employee', 'leaves', 'usedLeave', 'remainingLeave', 'leaveQuota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:cuti_tahunan,sakit,izin,cuti_khusus',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:10|max:500',
        ]);

        $user     = Auth::user();
        $employee = $user->employee;

        // Cek apakah ada pengajuan yang masih menunggu
        $pending = $employee->leaveRequests()
            ->where('status', 'menunggu')
            ->exists();

        if ($pending) {
            return back()->with('error', 'Anda masih memiliki pengajuan cuti yang sedang menunggu persetujuan.');
        }

        // Hitung hari kerja (Senin–Sabtu, kecuali Minggu)
        $start     = Carbon::parse($request->start_date);
        $end       = Carbon::parse($request->end_date);
        $period    = CarbonPeriod::create($start, $end);
        $totalDays = 0;
        foreach ($period as $date) {
            if (!$date->isSunday()) {
                $totalDays++;
            }
        }
        $totalDays = max(1, $totalDays);

        // Cek kuota cuti tahunan
        if ($request->type === 'cuti_tahunan') {
            $usedLeave = $employee->leaveRequests()
                ->where('type', 'cuti_tahunan')
                ->where('status', 'disetujui')
                ->whereYear('start_date', now()->year)
                ->sum('total_days');

            if (($usedLeave + $totalDays) > 12) {
                return back()->with('error', "Kuota cuti tahunan tidak mencukupi. Sisa: " . max(0, 12 - $usedLeave) . " hari.");
            }
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'type'        => $request->type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_days'  => $totalDays,
            'reason'      => $request->reason,
            'status'      => 'menunggu',
        ]);

        // ── Kirim notifikasi ke semua admin ───────────────────────────────
        $typeLabels = [
            'cuti_tahunan' => 'Cuti Tahunan',
            'sakit'        => 'Sakit',
            'izin'         => 'Izin',
            'cuti_khusus'  => 'Cuti Khusus',
        ];
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        if (!empty($adminIds)) {
            Notification::send(
                $adminIds,
                'leave_request',
                'Pengajuan Cuti Baru',
                "{$user->name} mengajukan {$typeLabels[$request->type]} selama {$totalDays} hari ({$request->start_date} s/d {$request->end_date}).",
                '🏖️',
                'bg-orange-100 dark:bg-orange-900/30',
                route('admin.leaves.index')
            );
        }

        ActivityLog::log('submit_leave', 'Leave',
            "{$user->name} mengajukan {$typeLabels[$request->type]} selama {$totalDays} hari ({$request->start_date} s/d {$request->end_date})"
        );

        return back()->with('success', "Pengajuan cuti {$totalDays} hari kerja berhasil dikirim! Menunggu persetujuan admin.");
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if ($leaveRequest->employee_id !== $employee->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($leaveRequest->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan yang sudah diproses tidak dapat dibatalkan.');
        }

        $leaveRequest->delete();

        ActivityLog::log('cancel_leave', 'Leave',
            "{$user->name} membatalkan pengajuan cuti ({$leaveRequest->type}) tanggal {$leaveRequest->start_date} s/d {$leaveRequest->end_date}"
        );

        return back()->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }
}
