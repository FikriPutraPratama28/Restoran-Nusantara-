@extends('admin.layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Sistem')
@section('page-subtitle', 'Konfigurasi branding & tampilan restoran')

@section('content')
@php
    $logoUrl    = \App\Models\SiteSetting::logoUrl();
    $faviconUrl = \App\Models\SiteSetting::faviconUrl();
    $s          = $settings; // shorthand
@endphp
<div class="max-w-4xl mx-auto space-y-5" x-data="{ tab: '{{ session('success_home') ? 'home' : 'branding' }}' }">

{{-- Flash --}}
@if(session('success_branding'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
    class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 flex items-center gap-3">
    <span class="text-xl">✅</span>
    <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success_branding') }}</p>
</div>
@endif
@if(session('success_home'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
    class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 flex items-center gap-3">
    <span class="text-xl">✅</span>
    <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success_home') }}</p>
</div>
@endif

{{-- ===== 2 BUTTON ACCORDION ===== --}}
<div class="grid grid-cols-2 gap-4">
    <button @click="tab = 'branding'"
        :class="tab === 'branding'
            ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/30 border-violet-600'
            : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:border-violet-400'"
        class="flex items-center gap-3 p-5 rounded-2xl border-2 transition-all duration-200 text-left">
        <div :class="tab==='branding' ? 'bg-white/20' : 'bg-violet-100 dark:bg-violet-900/30'"
            class="w-11 h-11 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🎨</div>
        <div>
            <p class="font-bold text-sm">Logo & Judul Restoran</p>
            <p :class="tab==='branding' ? 'text-violet-200' : 'text-gray-400 dark:text-slate-500'"
                class="text-xs mt-0.5">Nama, tagline, logo, warna</p>
        </div>
    </button>

    <button @click="tab = 'home'"
        :class="tab === 'home'
            ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30 border-orange-500'
            : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-700 hover:border-orange-400'"
        class="flex items-center gap-3 p-5 rounded-2xl border-2 transition-all duration-200 text-left">
        <div :class="tab==='home' ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30'"
            class="w-11 h-11 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🖼️</div>
        <div>
            <p class="font-bold text-sm">Tampilan Halaman Home</p>
            <p :class="tab==='home' ? 'text-orange-200' : 'text-gray-400 dark:text-slate-500'"
                class="text-xs mt-0.5">Gambar hero, judul, teks</p>
        </div>
    </button>
</div>

{{-- ===== PANEL: BRANDING ===== --}}
<div x-show="tab === 'branding'" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
<form method="POST" action="{{ route('admin.settings.branding') }}" enctype="multipart/form-data"
    x-data="{
        logoPreview: '{{ $logoUrl ?? '' }}',
        faviconPreview: '{{ $faviconUrl ?? '' }}',
        removeLogo: false, removeFavicon: false,
        onLogoChange(e) { const f=e.target.files[0]; if(f){this.logoPreview=URL.createObjectURL(f);this.removeLogo=false;} },
        onFaviconChange(e) { const f=e.target.files[0]; if(f){this.faviconPreview=URL.createObjectURL(f);this.removeFavicon=false;} }
    }">
    @csrf
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20">
            <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center text-base shadow">🎨</div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Branding Restoran</h3>
                <p class="text-xs text-gray-500 dark:text-slate-400">Logo, nama, dan identitas visual</p>
            </div>
        </div>
        <div class="p-6 space-y-5">

            {{-- Live Preview --}}
            <div class="bg-slate-900 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 flex-shrink-0">
                    <template x-if="logoPreview && !removeLogo">
                        <img :src="logoPreview" class="w-full h-full rounded-xl object-cover">
                    </template>
                    <template x-if="!logoPreview || removeLogo">
                        <div class="w-full h-full bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center text-lg">🍽️</div>
                    </template>
                </div>
                <div class="leading-none">
                    <div class="font-bold text-white text-sm" id="prev-name">{{ $s['restaurant_name'] ?? 'Restoran' }}</div>
                    <div class="font-bold tracking-widest text-[10px] uppercase" style="color:#f97316" id="prev-tag">{{ $s['restaurant_tagline'] ?? 'NUSANTARA' }}</div>
                </div>
                <span class="ml-auto text-xs text-slate-500 italic">Preview</span>
            </div>

            {{-- Logo & Favicon --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Logo --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Logo <span class="text-xs font-normal text-gray-400">PNG/JPG/SVG, maks 2MB</span></label>
                    <div class="border-2 border-dashed border-gray-200 dark:border-slate-600 rounded-2xl p-4 text-center hover:border-violet-400 transition-colors">
                        <template x-if="logoPreview && !removeLogo">
                            <img :src="logoPreview" class="w-16 h-16 rounded-xl object-cover mx-auto mb-2 shadow">
                        </template>
                        <template x-if="!logoPreview || removeLogo">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-2xl mx-auto mb-2">🖼️</div>
                        </template>
                        <label class="cursor-pointer block">
                            <span class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:underline">Pilih file logo</span>
                            <input type="file" name="logo" accept="image/*" class="hidden" @change="onLogoChange($event)">
                        </label>
                        @if($logoUrl)
                        <input type="hidden" name="remove_logo" :value="removeLogo ? '1' : '0'">
                        <button type="button" @click="removeLogo=true;logoPreview=''" x-show="!removeLogo"
                            class="text-xs text-red-500 hover:underline mt-1 block mx-auto">🗑️ Hapus</button>
                        <span x-show="removeLogo" class="text-xs text-red-400 mt-1 block">Akan dihapus saat simpan</span>
                        @endif
                    </div>
                </div>
                {{-- Favicon --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Favicon <span class="text-xs font-normal text-gray-400">ICO/PNG, maks 512KB</span></label>
                    <div class="border-2 border-dashed border-gray-200 dark:border-slate-600 rounded-2xl p-4 text-center hover:border-violet-400 transition-colors">
                        <template x-if="faviconPreview && !removeFavicon">
                            <img :src="faviconPreview" class="w-10 h-10 rounded-lg object-cover mx-auto mb-2 shadow">
                        </template>
                        <template x-if="!faviconPreview || removeFavicon">
                            <div class="w-10 h-10 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center text-xl mx-auto mb-2">🔖</div>
                        </template>
                        <label class="cursor-pointer block">
                            <span class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:underline">Pilih favicon</span>
                            <input type="file" name="favicon" accept="image/*,.ico" class="hidden" @change="onFaviconChange($event)">
                        </label>
                        @if($faviconUrl)
                        <input type="hidden" name="remove_favicon" :value="removeFavicon ? '1' : '0'">
                        <button type="button" @click="removeFavicon=true;faviconPreview=''" x-show="!removeFavicon"
                            class="text-xs text-red-500 hover:underline mt-1 block mx-auto">🗑️ Hapus</button>
                        <span x-show="removeFavicon" class="text-xs text-red-400 mt-1 block">Akan dihapus saat simpan</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nama & Tagline --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Nama Restoran <span class="text-red-500">*</span></label>
                    <input type="text" name="restaurant_name" value="{{ $s['restaurant_name'] ?? 'Restoran' }}"
                        @input="document.getElementById('prev-name').textContent=$event.target.value"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tagline <span class="text-red-500">*</span></label>
                    <input type="text" name="restaurant_tagline" value="{{ $s['restaurant_tagline'] ?? 'NUSANTARA' }}"
                        @input="document.getElementById('prev-tag').textContent=$event.target.value"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" required>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none resize-none"
                    placeholder="Deskripsi untuk SEO dan footer">{{ $s['description'] ?? '' }}</textarea>
            </div>

            {{-- Kontak --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">📍 Alamat</label>
                    <input type="text" name="address" value="{{ $s['address'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" placeholder="Jl. Kuliner No. 1">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">📞 Telepon</label>
                    <input type="text" name="phone" value="{{ $s['phone'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" placeholder="+62 812-xxxx-xxxx">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">✉️ Email</label>
                    <input type="email" name="email" value="{{ $s['email'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" placeholder="info@restoran.id">
                </div>
            </div>

            {{-- Warna --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">🎨 Warna Utama</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="primary_color" value="{{ $s['primary_color'] ?? '#f97316' }}"
                        id="colorPicker" class="w-12 h-10 rounded-lg border border-gray-200 dark:border-slate-600 cursor-pointer p-1">
                    <input type="text" id="colorText" value="{{ $s['primary_color'] ?? '#f97316' }}"
                        class="w-28 px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm font-mono text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" readonly>
                    <p class="text-xs text-gray-400">Warna aksen di seluruh tampilan</p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-2 border-t border-gray-100 dark:border-slate-700">
                <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-violet-600/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Branding
                </button>
            </div>
        </div>
    </div>
</form>
</div>

{{-- ===== PANEL: TAMPILAN HOME ===== --}}
<div x-show="tab === 'home'" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
<form method="POST" action="{{ route('admin.settings.home') }}" enctype="multipart/form-data"
    x-data="{
        bgPreview: '{{ $s['home_bg_image'] ?? '' }}',
        f1Preview: '{{ $s['home_float_img1'] ?? '' }}',
        f2Preview: '{{ $s['home_float_img2'] ?? '' }}',
        f3Preview: '{{ $s['home_float_img3'] ?? '' }}',
        onImg(field, e) {
            const f = e.target.files[0];
            if (f) this[field+'Preview'] = URL.createObjectURL(f);
        }
    }">
    @csrf
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20">
            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center text-base shadow">🖼️</div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Tampilan Halaman Home</h3>
                <p class="text-xs text-gray-500 dark:text-slate-400">Gambar latar, judul hero, teks, dan foto dekorasi</p>
            </div>
        </div>
        <div class="p-6 space-y-6">

            {{-- Teks Hero --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Teks Badge <span class="text-xs font-normal text-gray-400">(strip kecil di atas judul)</span>
                    </label>
                    <input type="text" name="home_badge_text" value="{{ $s['home_badge_text'] ?? 'Buka Sekarang · Estimasi 15-30 menit' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-orange-500 outline-none"
                        placeholder="Buka Sekarang · Estimasi 15-30 menit">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Judul Hero <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="home_hero_title" value="{{ $s['home_hero_title'] ?? 'Cita Rasa Nusantara di Ujung Jari' }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-orange-500 outline-none"
                        placeholder="Judul besar di halaman utama" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Sub-judul / Deskripsi Hero <span class="text-red-500">*</span>
                    </label>
                    <textarea name="home_hero_subtitle" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-orange-500 outline-none resize-none"
                        placeholder="Teks deskripsi di bawah judul hero" required>{{ $s['home_hero_subtitle'] ?? '' }}</textarea>
                </div>
            </div>

            {{-- Gambar Latar Belakang --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                    🌄 Gambar Latar Belakang Hero
                    <span class="text-xs font-normal text-gray-400 ml-1">Upload file atau masukkan URL</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Preview --}}
                    <div class="relative rounded-2xl overflow-hidden h-36 bg-gray-100 dark:bg-slate-700">
                        <img :src="bgPreview || 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=200&fit=crop'"
                            class="w-full h-full object-cover" alt="Preview BG">
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                            <span class="text-white text-xs font-semibold bg-black/50 px-3 py-1 rounded-full">Preview Latar</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 dark:border-slate-600 rounded-xl p-4 cursor-pointer hover:border-orange-400 transition-colors">
                            <span class="text-2xl mb-1">📁</span>
                            <span class="text-xs font-semibold text-orange-600 dark:text-orange-400">Upload gambar</span>
                            <span class="text-[10px] text-gray-400 mt-0.5">JPG/PNG/WEBP, maks 5MB</span>
                            <input type="file" name="home_bg_image" accept="image/*" class="hidden"
                                @change="onImg('bg', $event)">
                        </label>
                        <div>
                            <input type="url" name="home_bg_image_url"
                                value="{{ str_starts_with($s['home_bg_image'] ?? '', 'http') ? $s['home_bg_image'] : '' }}"
                                @input="bgPreview = $event.target.value"
                                class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-xs text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-orange-500 outline-none"
                                placeholder="Atau masukkan URL gambar...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3 Foto Dekorasi Melayang --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3">
                    🍽️ Foto Dekorasi Melayang (kanan hero)
                    <span class="text-xs font-normal text-gray-400 ml-1">3 foto bulat di sisi kanan</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach([
                        ['key'=>'home_float_img1', 'label'=>'Foto 1 (Besar)', 'xvar'=>'f1'],
                        ['key'=>'home_float_img2', 'label'=>'Foto 2 (Kecil)', 'xvar'=>'f2'],
                        ['key'=>'home_float_img3', 'label'=>'Foto 3 (Tengah)', 'xvar'=>'f3'],
                    ] as $img)
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-gray-600 dark:text-slate-400">{{ $img['label'] }}</p>
                        <div class="relative rounded-xl overflow-hidden h-24 bg-gray-100 dark:bg-slate-700">
                            <img :src="{{ $img['xvar'] }}Preview || '{{ $s[$img['key']] ?? '' }}'"
                                class="w-full h-full object-cover" alt="{{ $img['label'] }}">
                        </div>
                        <label class="flex items-center justify-center gap-1.5 border border-dashed border-gray-200 dark:border-slate-600 rounded-xl py-2 cursor-pointer hover:border-orange-400 transition-colors">
                            <span class="text-xs font-semibold text-orange-600 dark:text-orange-400">📁 Upload</span>
                            <input type="file" name="{{ $img['key'] }}" accept="image/*" class="hidden"
                                @change="onImg('{{ $img['xvar'] }}', $event)">
                        </label>
                        <input type="url" name="{{ $img['key'] }}_url"
                            value="{{ str_starts_with($s[$img['key']] ?? '', 'http') ? $s[$img['key']] : '' }}"
                            @input="{{ $img['xvar'] }}Preview = $event.target.value"
                            class="w-full px-3 py-1.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-xs text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-orange-500 outline-none"
                            placeholder="URL gambar...">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-2 border-t border-gray-100 dark:border-slate-700">
                <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-orange-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Tampilan Home
                </button>
            </div>
        </div>
    </div>
</form>
</div>

{{-- ===== INFO SISTEM (selalu tampil) ===== --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
    <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 text-sm">
        <span class="w-6 h-6 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center text-xs">ℹ️</span>
        Informasi Sistem
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach($sysInfo as $info)
        <div class="flex items-center justify-between p-2.5 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
            <span class="text-xs text-gray-500 dark:text-slate-400">{{ $info['label'] }}</span>
            <span class="text-xs font-semibold text-gray-800 dark:text-slate-200">{{ $info['value'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== STATISTIK DB ===== --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
    <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 text-sm">
        <span class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-xs">🗄️</span>
        Statistik Database
    </h3>
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
        @foreach($dbStats as $stat)
        <div class="p-2.5 bg-gray-50 dark:bg-slate-700/50 rounded-xl text-center">
            <div class="text-xl mb-0.5">{{ $stat['icon'] }}</div>
            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $stat['value'] }}</div>
            <div class="text-[10px] text-gray-500 dark:text-slate-400">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

</div>{{-- end max-w-4xl --}}

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cp = document.getElementById('colorPicker');
    const ct = document.getElementById('colorText');
    if (cp && ct) cp.addEventListener('input', () => ct.value = cp.value);
});
</script>
@endsection
