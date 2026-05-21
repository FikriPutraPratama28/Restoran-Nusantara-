<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Login ────────────────────────────────────────────────────────────

    public function loginPage()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:6|max:100',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate(); // Cegah session fixation

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->withInput($request->only('email'));
            }

            ActivityLog::log('login', 'Auth', "{$user->name} ({$user->role}) berhasil login", $user);

            return $this->redirectByRole($user);
        }

        ActivityLog::log('login_failed', 'Auth', "Percobaan login gagal untuk email: {$request->email}");

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    // ── Register ─────────────────────────────────────────────────────────

    public function registerPage()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::create([
            'name'      => strip_tags($data['name']),
            'email'     => strtolower(trim($data['email'])),
            'phone'     => $data['phone'] ?? null,
            'password'  => Hash::make($data['password']),
            'role'      => 'pelanggan',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::log('register', 'Auth', "Pelanggan baru mendaftar: {$user->name} ({$user->email})", $user);

        return redirect()->route('home')
            ->with('success', 'Selamat datang, ' . $user->name . '! Akun Anda berhasil dibuat.');
    }

    // ── Logout ───────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::log('logout', 'Auth', "{$user->name} ({$user->role}) logout", $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Berhasil logout. Sampai jumpa!');
    }

    // ── Profile ──────────────────────────────────────────────────────────

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'   => 'required|string|min:2|max:100',
            'phone'  => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Sanitasi nama
        $data['name'] = strip_tags($data['name']);

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // ── Helper ───────────────────────────────────────────────────────────

    private function redirectByRole(User $user)
    {
        return match($user->role) {
            'admin'    => redirect()->intended(route('admin.dashboard')),
            'karyawan' => redirect()->intended(route('karyawan.dashboard')),
            default    => redirect()->intended(route('home')),
        };
    }
}
