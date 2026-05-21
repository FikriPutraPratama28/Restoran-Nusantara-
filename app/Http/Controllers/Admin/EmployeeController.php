<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    // ── Index ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Employee::with('user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%'));
            })
            ->when($request->filled('jabatan'), fn($q) => $q->where('jabatan', $request->jabatan))
            ->when($request->filled('shift'),   fn($q) => $q->where('shift', $request->shift))
            ->when($request->filled('status'),  fn($q) => $q->where('status', $request->status));

        $employees = $query->latest()->get();

        $stats = [
            'total'    => Employee::count(),
            'aktif'    => Employee::where('status', 'aktif')->count(),
            'cuti'     => Employee::where('status', 'cuti')->count(),
            'nonaktif' => Employee::where('status', 'nonaktif')->count(),
        ];

        return view('admin.employees.index', compact('employees', 'stats'));
    }

    // ── Create / Store ────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|min:2|max:100',
            'email'             => 'required|email|max:255|unique:users,email',
            'phone'             => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'password'          => 'required|string|min:8|max:100',
            'jabatan'           => 'required|string|max:100',
            'shift'             => 'required|in:pagi,siang,malam,full',
            'join_date'         => 'required|date|before_or_equal:today',
            'address'           => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'salary'            => 'nullable|numeric|min:0|max:99999999',
            'notes'             => 'nullable|string|max:1000',
            'avatar'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Buat user dengan role karyawan
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'      => strip_tags($request->name),
            'email'     => strtolower(trim($request->email)),
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => 'karyawan',
            'avatar'    => $avatarPath,
            'is_active' => true,
        ]);

        // Buat data karyawan
        $employee = Employee::create([
            'user_id'           => $user->id,
            'employee_code'     => Employee::generateCode(),
            'jabatan'           => $request->jabatan,
            'shift'             => $request->shift,
            'join_date'         => $request->join_date,
            'address'           => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'salary'            => $request->salary,
            'notes'             => $request->notes,
            'status'            => 'aktif',
        ]);

        ActivityLog::log('create_employee', 'Employee',
            "Menambahkan karyawan baru: {$user->name} ({$employee->employee_code}), jabatan: {$request->jabatan}, shift: {$request->shift}",
            $employee
        );

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan "' . $request->name . '" berhasil ditambahkan!');
    }

    // ── Edit / Update ─────────────────────────────────────────────────────
    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'              => 'required|string|min:2|max:100',
            'email'             => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'phone'             => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'jabatan'           => 'required|string|max:100',
            'shift'             => 'required|in:pagi,siang,malam,full',
            'join_date'         => 'required|date|before_or_equal:today',
            'address'           => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'salary'            => 'nullable|numeric|min:0|max:99999999',
            'status'            => 'required|in:aktif,cuti,nonaktif',
            'notes'             => 'nullable|string|max:1000',
            'avatar'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'password'          => 'nullable|string|min:8|max:100',
        ]);

        $user = $employee->user;

        $userData = [
            'name'  => strip_tags($request->name),
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Simpan shift lama sebelum update
        $oldShift = $employee->shift;
        $oldJabatan = $employee->jabatan;

        $employee->update([
            'jabatan'           => $request->jabatan,
            'shift'             => $request->shift,
            'join_date'         => $request->join_date,
            'address'           => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'salary'            => $request->salary,
            'status'            => $request->status,
            'notes'             => $request->notes,
        ]);

        ActivityLog::log('update_employee', 'Employee',
            "Memperbarui data karyawan: {$request->name} ({$employee->employee_code}), jabatan: {$request->jabatan}, status: {$request->status}",
            $employee
        );

        // ── Notifikasi jadwal berubah ke karyawan ─────────────────────────
        $shiftLabels = [
            'pagi'  => 'Pagi (06:00–14:00)',
            'siang' => 'Siang (14:00–22:00)',
            'malam' => 'Malam (22:00–06:00)',
            'full'  => 'Full Day (08:00–17:00)',
        ];
        $shiftChanged   = $oldShift !== $request->shift;
        $jabatanChanged = $oldJabatan !== $request->jabatan;

        if ($shiftChanged || $jabatanChanged) {
            $changes = [];
            if ($shiftChanged) {
                $changes[] = "shift dari {$shiftLabels[$oldShift]} menjadi {$shiftLabels[$request->shift]}";
            }
            if ($jabatanChanged) {
                $changes[] = "jabatan dari {$oldJabatan} menjadi {$request->jabatan}";
            }
            Notification::send(
                $employee->user_id,
                'schedule_changed',
                'Jadwal Kerja Diperbarui 📅',
                'Admin memperbarui ' . implode(' dan ', $changes) . '.',
                '📅',
                'bg-blue-100 dark:bg-blue-900/30',
                route('karyawan.schedule')
            );
            ActivityLog::log('schedule_changed', 'Employee',
                "Jadwal {$request->name} diubah: " . implode(', ', $changes),
                $employee
            );
        }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan "' . $request->name . '" berhasil diperbarui!');
    }

    // ── Destroy ───────────────────────────────────────────────────────────

    public function destroy(Employee $employee)
    {
        $name = $employee->user->name;
        $code = $employee->employee_code;
        $user = $employee->user;

        if ($user->avatar) Storage::disk('public')->delete($user->avatar);

        ActivityLog::log('delete_employee', 'Employee',
            "Menghapus karyawan: {$name} ({$code})"
        );

        // Hapus user (cascade akan hapus employee & attendance)
        $user->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan "' . $name . '" berhasil dihapus!');
    }

    // ── Show (Detail) ─────────────────────────────────────────────────────

    public function show(Employee $employee)
    {
        $employee->load('user');

        $month = request('month', now()->month);
        $year  = request('year', now()->year);

        $attendances = $employee->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        $leaveRequests = $employee->leaveRequests()
            ->latest()
            ->take(5)
            ->get();

        $attendanceStats = [
            'hadir'     => $attendances->where('status', 'hadir')->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin'      => $attendances->where('status', 'izin')->count(),
            'sakit'     => $attendances->where('status', 'sakit')->count(),
            'alpha'     => $attendances->where('status', 'alpha')->count(),
        ];

        return view('admin.employees.show', compact(
            'employee', 'attendances', 'leaveRequests', 'attendanceStats', 'month', 'year'
        ));
    }

    // ── Absensi Admin ─────────────────────────────────────────────────────

    public function attendanceIndex(Request $request)
    {
        $date  = $request->get('date', today()->toDateString());
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        $attendances = Attendance::with('employee.user')
            ->whereDate('date', $date)
            ->latest()
            ->get();

        // Karyawan yang belum absen hari ini
        $allEmployees = Employee::with('user')->where('status', 'aktif')->get();
        $presentIds   = $attendances->pluck('employee_id');
        $absentEmployees = $allEmployees->whereNotIn('id', $presentIds);

        $stats = [
            'hadir'     => $attendances->where('status', 'hadir')->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin'      => $attendances->where('status', 'izin')->count(),
            'sakit'     => $attendances->where('status', 'sakit')->count(),
            'alpha'     => $absentEmployees->count(),
            'total'     => $allEmployees->count(),
        ];

        return view('admin.employees.attendance', compact(
            'attendances', 'absentEmployees', 'stats', 'date'
        ));
    }

    // ── Leave Requests Admin ──────────────────────────────────────────────

    public function leaveIndex(Request $request)
    {
        $leaves = \App\Models\LeaveRequest::with('employee.user')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        return view('admin.employees.leaves', compact('leaves'));
    }

    public function leaveApprove(Request $request, \App\Models\LeaveRequest $leave)
    {
        $request->validate([
            'action'      => 'required|in:disetujui,ditolak',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $leave->update([
            'status'      => $request->action,
            'admin_notes' => $request->admin_notes,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Jika disetujui, update status karyawan jadi cuti
        if ($request->action === 'disetujui') {
            $leave->employee->update(['status' => 'cuti']);
        }

        // Jika ditolak dan sebelumnya cuti, kembalikan ke aktif
        if ($request->action === 'ditolak' && $leave->employee->status === 'cuti') {
            $leave->employee->update(['status' => 'aktif']);
        }

        // ── Kirim notifikasi ke karyawan ──────────────────────────────────
        $karyawanUserId = $leave->employee->user_id;
        if ($request->action === 'disetujui') {
            Notification::send(
                $karyawanUserId,
                'leave_approved',
                'Cuti Disetujui ✅',
                "Pengajuan cuti {$leave->type_label} Anda ({$leave->total_days} hari) telah disetujui oleh admin." .
                ($request->admin_notes ? " Catatan: {$request->admin_notes}" : ''),
                '✅',
                'bg-emerald-100 dark:bg-emerald-900/30',
                route('karyawan.leave')
            );
        } else {
            Notification::send(
                $karyawanUserId,
                'leave_rejected',
                'Cuti Ditolak ❌',
                "Pengajuan cuti {$leave->type_label} Anda ({$leave->total_days} hari) ditolak." .
                ($request->admin_notes ? " Alasan: {$request->admin_notes}" : ''),
                '❌',
                'bg-red-100 dark:bg-red-900/30',
                route('karyawan.leave')
            );
        }

        $label = $request->action === 'disetujui' ? 'disetujui ✅' : 'ditolak ❌';

        // ── Activity Log ──────────────────────────────────────────────────
        $karyawanName = $leave->employee->user->name;
        $actionKey    = $request->action === 'disetujui' ? 'approve_leave' : 'reject_leave';
        $actionDesc   = $request->action === 'disetujui' ? 'menyetujui' : 'menolak';
        ActivityLog::log($actionKey, 'Leave',
            "Admin {$actionDesc} pengajuan cuti {$leave->type_label} milik {$karyawanName} ({$leave->total_days} hari)",
            $leave
        );

        return back()->with('success', "Pengajuan cuti berhasil {$label}");
    }
}
