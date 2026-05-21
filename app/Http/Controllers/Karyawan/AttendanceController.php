<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // ── Halaman Absensi ───────────────────────────────────────────────────

    public function index()
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('karyawan.dashboard');
        }

        $today       = today();
        $todayAttend = $employee->todayAttendance();

        // Generate QR token harian (berlaku 1 hari, unik per karyawan)
        $qrToken = hash('sha256', $employee->id . $today->toDateString() . config('app.key'));

        // Riwayat absensi
        $history = $employee->attendances()
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('karyawan.attendance', compact(
            'employee', 'todayAttend', 'qrToken', 'history', 'today'
        ));
    }

    // ── Check In ──────────────────────────────────────────────────────────

    public function checkIn(Request $request)
    {
        $request->validate([
            'photo'     => 'nullable|string|max:2000000', // base64 maks ~1.5MB
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'qr_token'  => 'nullable|string|max:128',
        ]);

        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Data karyawan tidak ditemukan.'], 422);
        }

        // Cek sudah absen hari ini
        if ($employee->todayAttendance()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini.',
            ], 422);
        }

        $now           = now();
        $lateThreshold = Carbon::parse(Attendance::lateThreshold());
        $isLate        = $now->gt($lateThreshold);
        $lateMinutes   = $isLate ? (int) $now->diffInMinutes($lateThreshold) : 0;

        // Simpan foto selfie jika ada (base64)
        $photoPath = null;
        if ($request->filled('photo')) {
            $photoPath = $this->saveBase64Photo($request->photo, 'attendance/checkin');
        }

        $attendance = Attendance::create([
            'employee_id'    => $employee->id,
            'date'           => today(),
            'check_in'       => $now->format('H:i:s'),
            'status'         => $isLate ? 'terlambat' : 'hadir',
            'late_minutes'   => $lateMinutes,
            'check_in_photo' => $photoPath,
            'check_in_lat'   => $request->latitude,
            'check_in_lng'   => $request->longitude,
            'qr_token'       => $request->qr_token,
        ]);

        $message = $isLate
            ? "Check-in berhasil! Anda terlambat {$lateMinutes} menit."
            : 'Check-in berhasil! Selamat bekerja 💪';

        // ── Notifikasi ke admin jika terlambat ────────────────────────────
        if ($isLate) {
            $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
            if (!empty($adminIds)) {
                Notification::send(
                    $adminIds,
                    'attendance_late',
                    'Karyawan Terlambat ⏰',
                    "{$user->name} check-in terlambat {$lateMinutes} menit pada " . $now->format('H:i') . '.',
                    '⏰',
                    'bg-yellow-100 dark:bg-yellow-900/30',
                    route('admin.attendance.index')
                );
            }
        }

        // ── Activity Log ──────────────────────────────────────────────────
        $action = $isLate ? 'checkin_late' : 'checkin';
        $desc   = $isLate
            ? "{$user->name} check-in terlambat {$lateMinutes} menit pukul " . $now->format('H:i')
            : "{$user->name} check-in tepat waktu pukul " . $now->format('H:i');
        ActivityLog::log($action, 'Attendance', $desc, $attendance);

        return response()->json([
            'success'      => true,
            'message'      => $message,
            'check_in'     => $now->format('H:i'),
            'status'       => $attendance->status,
            'is_late'      => $isLate,
            'late_minutes' => $lateMinutes,
        ]);
    }

    // ── Check Out ─────────────────────────────────────────────────────────

    public function checkOut(Request $request)
    {
        $request->validate([
            'photo'     => 'nullable|string|max:2000000',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $user       = Auth::user();
        $employee   = $user->employee;
        $attendance = $employee?->todayAttendance();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini.',
            ], 422);
        }

        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini.',
            ], 422);
        }

        $now = now();

        $photoPath = null;
        if ($request->filled('photo')) {
            $photoPath = $this->saveBase64Photo($request->photo, 'attendance/checkout');
        }

        $attendance->update([
            'check_out'       => $now->format('H:i:s'),
            'check_out_photo' => $photoPath,
            'check_out_lat'   => $request->latitude,
            'check_out_lng'   => $request->longitude,
        ]);

        $duration = $attendance->fresh()->work_duration ?? '-';

        ActivityLog::log('checkout', 'Attendance',
            "{$user->name} check-out pukul " . $now->format('H:i') . ", durasi kerja: {$duration}",
            $attendance->fresh()
        );

        return response()->json([
            'success'   => true,
            'message'   => "Check-out berhasil! Durasi kerja: {$duration}. Selamat istirahat 🏠",
            'check_out' => $now->format('H:i'),
            'duration'  => $duration,
        ]);
    }

    // ── Check In Web (Form POST biasa) ───────────────────────────────────

    public function checkInWeb(Request $request)
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('karyawan.attendance')->with('absen_error', 'Data karyawan tidak ditemukan.');
        }

        if ($employee->todayAttendance()) {
            return redirect()->route('karyawan.attendance')->with('absen_error', 'Anda sudah melakukan check-in hari ini.');
        }

        $now           = now();
        $lateThreshold = Carbon::parse(Attendance::lateThreshold());
        $isLate        = $now->gt($lateThreshold);
        $lateMinutes   = $isLate ? (int) $now->diffInMinutes($lateThreshold) : 0;

        $attendance = Attendance::create([
            'employee_id'  => $employee->id,
            'date'         => today(),
            'check_in'     => $now->format('H:i:s'),
            'status'       => $isLate ? 'terlambat' : 'hadir',
            'late_minutes' => $lateMinutes,
        ]);

        if ($isLate) {
            $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
            if (!empty($adminIds)) {
                Notification::send($adminIds, 'attendance_late', 'Karyawan Terlambat ⏰',
                    "{$user->name} check-in terlambat {$lateMinutes} menit pada " . $now->format('H:i') . '.',
                    '⏰', 'bg-yellow-100 dark:bg-yellow-900/30', route('admin.attendance.index'));
            }
        }

        ActivityLog::log($isLate ? 'checkin_late' : 'checkin', 'Attendance',
            "{$user->name} check-in " . ($isLate ? "terlambat {$lateMinutes} menit" : "tepat waktu") . " pukul " . $now->format('H:i'),
            $attendance
        );

        $msg = $isLate
            ? "Check-in berhasil! Anda terlambat {$lateMinutes} menit. Pukul " . $now->format('H:i')
            : "Check-in berhasil! Selamat bekerja 💪 Pukul " . $now->format('H:i');

        // TTS via session flag — diputar di halaman setelah redirect
        return redirect()->route('karyawan.attendance')
            ->with('absen_success', $msg)
            ->with('absen_tts', 'checkin');
    }

    // ── Check Out Web (Form POST biasa) ──────────────────────────────────

    public function checkOutWeb(Request $request)
    {
        $user       = Auth::user();
        $employee   = $user->employee;
        $attendance = $employee?->todayAttendance();

        if (!$attendance) {
            return redirect()->route('karyawan.attendance')->with('absen_error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out) {
            return redirect()->route('karyawan.attendance')->with('absen_error', 'Anda sudah melakukan check-out hari ini.');
        }

        $now = now();
        $attendance->update(['check_out' => $now->format('H:i:s')]);
        $duration = $attendance->fresh()->work_duration ?? '-';

        ActivityLog::log('checkout', 'Attendance',
            "{$user->name} check-out pukul " . $now->format('H:i') . ", durasi: {$duration}",
            $attendance->fresh()
        );

        return redirect()->route('karyawan.attendance')
            ->with('absen_success', "Check-out berhasil! Durasi kerja: {$duration}. Selamat istirahat 🏠")
            ->with('absen_tts', 'checkout');
    }

    // ── QR Code Absensi ───────────────────────────────────────────────────
    public function qrScan(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
            'type'     => 'required|in:check_in,check_out',
        ]);

        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Data karyawan tidak ditemukan.'], 422);
        }

        // Validasi token QR
        $expectedToken = hash('sha256', $employee->id . today()->toDateString() . config('app.key'));

        if ($request->qr_token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau sudah kadaluarsa.',
            ], 422);
        }

        if ($request->type === 'check_in') {
            return $this->checkIn($request);
        }

        return $this->checkOut($request);
    }

    // ── Helper: Simpan foto base64 ────────────────────────────────────────

    private function saveBase64Photo(string $base64, string $folder): ?string
    {
        try {
            // Format: data:image/jpeg;base64,/9j/4AAQ...
            if (str_contains($base64, ',')) {
                $base64 = explode(',', $base64)[1];
            }
            $imageData = base64_decode($base64);
            if (!$imageData) return null;

            $filename = $folder . '/' . uniqid() . '_' . time() . '.jpg';
            Storage::disk('public')->put($filename, $imageData);
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}
