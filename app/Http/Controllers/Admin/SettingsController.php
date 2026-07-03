<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::allCached();

        $sysInfo = [
            ['label' => 'Versi Aplikasi',  'value' => 'v1.0.0'],
            ['label' => 'Laravel Version', 'value' => app()->version()],
            ['label' => 'PHP Version',     'value' => PHP_VERSION],
            ['label' => 'Environment',     'value' => app()->environment()],
            ['label' => 'Debug Mode',      'value' => config('app.debug') ? 'Aktif' : 'Nonaktif'],
            ['label' => 'Timezone',        'value' => config('app.timezone')],
            ['label' => 'Database',        'value' => config('database.default') . ' — ' . config('database.connections.mysql.database')],
            ['label' => 'Cache Driver',    'value' => config('cache.default')],
        ];

        $admins = \App\Models\User::where('role', 'admin')->get();

        $dbStats = [
            ['label' => 'Total Users',    'value' => \App\Models\User::count(),         'icon' => 'users'],
            ['label' => 'Total Menu',     'value' => \App\Models\Menu::count(),          'icon' => 'menu'],
            ['label' => 'Total Promo',    'value' => \App\Models\Promo::count(),         'icon' => 'promo'],
        ];

        return view('admin.settings', compact('settings', 'sysInfo', 'admins', 'dbStats'));
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'restaurant_name'    => 'required|string|max:100',
            'restaurant_tagline' => 'required|string|max:100',
            'description'        => 'nullable|string|max:500',
            'address'            => 'nullable|string|max:255',
            'phone'              => 'nullable|string|max:30',
            'email'              => 'nullable|email|max:100',
            'primary_color'      => 'nullable|string|max:20',
            'logo'               => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'favicon'            => 'nullable|image|mimes:jpeg,png,jpg,webp,ico|max:512',
        ]);

        // Simpan text settings
        $textKeys = ['restaurant_name', 'restaurant_tagline', 'description', 'address', 'phone', 'email', 'primary_color'];
        foreach ($textKeys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, strip_tags($request->input($key)));
            }
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            $old = SiteSetting::get('logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('logo')->store('branding', 'public');
            SiteSetting::set('logo', $path);
        }

        // Hapus logo
        if ($request->input('remove_logo') === '1') {
            $old = SiteSetting::get('logo');
            if ($old) Storage::disk('public')->delete($old);
            SiteSetting::set('logo', null);
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            $old = SiteSetting::get('favicon');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('favicon')->store('branding', 'public');
            SiteSetting::set('favicon', $path);
        }

        // Hapus favicon
        if ($request->input('remove_favicon') === '1') {
            $old = SiteSetting::get('favicon');
            if ($old) Storage::disk('public')->delete($old);
            SiteSetting::set('favicon', null);
        }

        SiteSetting::clearCache();

        ActivityLog::log('update_branding', 'Content',
            'Admin memperbarui branding restoran: nama "' . $request->restaurant_name . '" / tagline "' . $request->restaurant_tagline . '"'
        );

        return back()->with('success_branding', 'Branding restoran berhasil diperbarui!');
    }

    public function updateHome(Request $request)
    {
        $request->validate([
            'home_hero_title'    => 'required|string|max:200',
            'home_hero_subtitle' => 'required|string|max:500',
            'home_badge_text'    => 'nullable|string|max:100',
            'home_bg_image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'home_bg_image_url'  => 'nullable|url|max:500',
            'home_float_img1'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'home_float_img1_url'=> 'nullable|url|max:500',
            'home_float_img2'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'home_float_img2_url'=> 'nullable|url|max:500',
            'home_float_img3'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'home_float_img3_url'=> 'nullable|url|max:500',
        ]);

        // Text fields
        foreach (['home_hero_title', 'home_hero_subtitle', 'home_badge_text'] as $key) {
            SiteSetting::set($key, strip_tags($request->input($key, '')));
        }

        // Image fields — upload file atau simpan URL
        $imageFields = ['home_bg_image', 'home_float_img1', 'home_float_img2', 'home_float_img3'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $old = SiteSetting::get($field);
                // Hapus file lama hanya jika bukan URL eksternal
                if ($old && !str_starts_with($old, 'http')) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file($field)->store('home', 'public');
                SiteSetting::set($field, asset('storage/' . $path));
            } elseif ($request->filled($field . '_url')) {
                SiteSetting::set($field, $request->input($field . '_url'));
            }
        }

        SiteSetting::clearCache();

        ActivityLog::log('update_home', 'Content',
            'Admin memperbarui tampilan halaman Home: judul "' . $request->home_hero_title . '"'
        );

        return back()->with('success_home', 'Tampilan halaman Home berhasil diperbarui!');
    }
}
