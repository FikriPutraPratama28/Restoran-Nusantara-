<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua user dengan filter role & search.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('users'));
    }

    /**
     * Tambah user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'role'      => 'required|in:super_admin,admin',
            'is_active' => 'boolean',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        ActivityLog::log(
            'create_user',
            'User',
            "Menambahkan user baru: \"{$user->name}\" ({$user->email}, role: {$user->role_label})",
            $user
        );

        return redirect()->route('admin.users')
            ->with('success', "User \"{$user->name}\" berhasil ditambahkan!");
    }

    /**
     * Update user. Password opsional — jika kosong tidak diubah.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'  => 'nullable|string|min:6',
            'role'      => 'required|in:super_admin,admin',
            'is_active' => 'boolean',
        ]);

        // Jangan ubah password jika kosong
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $user->update($validated);

        ActivityLog::log(
            'update_user',
            'User',
            "Memperbarui user: \"{$user->name}\" ({$user->email}, role: {$user->role_label})",
            $user
        );

        return redirect()->route('admin.users')
            ->with('success', "User \"{$user->name}\" berhasil diperbarui!");
    }

    /**
     * Hapus user.
     * Tidak bisa menghapus diri sendiri atau super_admin terakhir.
     */
    public function destroy(User $user)
    {
        // Tidak boleh hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Tidak boleh hapus super_admin terakhir
        if ($user->role === 'super_admin') {
            $superAdminCount = User::where('role', 'super_admin')->count();
            if ($superAdminCount <= 1) {
                return redirect()->route('admin.users')
                    ->with('error', 'Tidak dapat menghapus Super Admin terakhir di sistem.');
            }
        }

        $name  = $user->name;
        $email = $user->email;

        ActivityLog::log(
            'delete_user',
            'User',
            "Menghapus user: \"{$name}\" ({$email})"
        );

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', "User \"{$name}\" berhasil dihapus!");
    }

    /**
     * Toggle is_active via AJAX — return JSON.
     */
    public function toggleActive(User $user)
    {
        // Tidak boleh nonaktifkan diri sendiri
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.',
            ], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'aktif' : 'nonaktif';

        ActivityLog::log(
            'toggle_user_active',
            'User',
            "Mengubah status user \"{$user->name}\" menjadi {$status}",
            $user
        );

        return response()->json([
            'success'   => true,
            'is_active' => $user->is_active,
            'message'   => "Status user \"{$user->name}\" berhasil diubah menjadi {$status}.",
        ]);
    }
}
