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
    class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-emerald-600 dark:text-emerald-400 text-sm font-medium font-jakarta">{{ session('success_branding') }}</p>
</div>
@endif
@if(session('success_home'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
    class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-emerald-600 dark:text-emerald-400 text-sm font-medium font-jakarta">{{ session('success_home') }}</p>
</div>
@endif

{{-- ===== 2 BUTTON ACCORDION ===== --}}
<div class="grid grid-cols-2 gap-4">
    <button @click="tab = 'branding'"
        :class="tab === 'branding'
            ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/30 border-violet-600'
            : 'bg-white dark:bg-admin-card text-gray-500 dark:text-slate-400 border-gray-200 dark:border-slate-700 hover:border-violet-500/50'"
        class="flex items-center gap-3 p-5 rounded-2xl border transition-all duration-200 text-left">
        <div :class="tab==='branding' ? 'bg-white/20 text-white' : 'bg-violet-950/30 text-violet-400'"
            class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122l9.37-9.445m-1.414-1.414l-9.37 9.445M12.877 19c-.767.146-1.566.22-2.377.22a8.623 8.623 0 01-8.5-8.623c0-4.76 3.805-8.622 8.5-8.622s8.5 3.862 8.5 8.622c0 .817-.113 1.608-.328 2.36M15.422 13.567l-2.072 2.087"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-sm font-jakarta text-gray-900 dark:text-slate-100">Logo & Judul Restoran</p>
            <p :class="tab==='branding' ? 'text-violet-200' : 'text-slate-500'"
                class="text-xs mt-0.5 font-medium">Nama, tagline, logo, warna</p>
        </div>
    </button>

    <button @click="tab = 'home'"
        :class="tab === 'home'
            ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30 border-orange-500'
            : 'bg-white dark:bg-admin-card text-gray-500 dark:text-slate-400 border-gray-200 dark:border-slate-700 hover:border-orange-500/50'"
        class="flex items-center gap-3 p-5 rounded-2xl border transition-all duration-200 text-left">
        <div :class="tab==='home' ? 'bg-white/20 text-white' : 'bg-orange-950/30 text-orange-400'"
            class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-sm font-jakarta text-gray-900 dark:text-slate-100">Tampilan Halaman Home</p>
            <p :class="tab==='home' ? 'text-orange-200' : 'text-slate-500'"
                class="text-xs mt-0.5 font-medium">Gambar hero, judul, teks</p>
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
    <div class="bg-white dark:bg-admin-card rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-white/[0.07]">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-white/[0.06] bg-gradient-to-r from-violet-50 dark:from-violet-900/20 to-purple-50 dark:to-purple-900/20">
            <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-sm">Branding Restoran</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-500 font-medium">Logo, nama, dan identitas visual</p>
            </div>
        </div>
        <div class="p-6 space-y-5">

            {{-- Live Preview --}}
            <div class="bg-slate-950/80 rounded-2xl p-4 flex items-center gap-3 border border-slate-800">
                <div class="w-10 h-10 flex-shrink-0">
                    <template x-if="logoPreview && !removeLogo">
                        <img :src="logoPreview" class="w-full h-full rounded-xl object-cover">
                    </template>
                    <template x-if="!logoPreview || removeLogo">
                        <div class="w-full h-full bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.164 4.836A9 9 0 005.636 19.364m14.528-14.528A9 9 0 015.636 19.364m14.528-14.528L5.636 19.364"/>
                            </svg>
                        </div>
                    </template>
                </div>
                <div class="leading-none">
                    <div class="font-bold text-white text-sm font-jakarta" id="prev-name">{{ $s['restaurant_name'] ?? 'Restoran' }}</div>
                    <div class="font-extrabold tracking-widest text-[10px] uppercase font-jakarta mt-1" style="color:#f97316" id="prev-tag">{{ $s['restaurant_tagline'] ?? 'NUSANTARA' }}</div>
                </div>
                <span class="ml-auto text-xs text-slate-600 font-bold uppercase tracking-wider font-jakarta">Preview</span>
            </div>

            {{-- Logo & Favicon --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Logo --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 font-jakarta">Logo <span class="text-[10px] font-normal text-slate-500">PNG/JPG/SVG, maks 2MB</span></label>
                    <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 hover:border-violet-500/50 rounded-2xl p-4 text-center transition-colors bg-gray-50/50 dark:bg-slate-950/20">
                            <template x-if="logoPreview && !removeLogo">
                            <img :src="logoPreview" class="w-16 h-16 rounded-xl object-cover mx-auto mb-2 shadow-lg border border-slate-700">
                        </template>
                        <template x-if="!logoPreview || removeLogo">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-gray-400 dark:text-slate-500 mx-auto mb-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </template>
                        <label class="cursor-pointer block">
                            <span class="text-xs font-bold text-violet-400 hover:text-violet-300 transition-colors">Pilih file logo</span>
                            <input type="file" name="logo" accept="image/*" class="hidden" @change="onLogoChange($event)">
                        </label>
                        @if($logoUrl)
                        <input type="hidden" name="remove_logo" :value="removeLogo ? '1' : '0'">
                        <button type="button" @click="removeLogo=true;logoPreview=''" x-show="!removeLogo"
                            class="text-xs text-red-400 hover:text-red-300 font-semibold mt-2 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Logo
                        </button>
                        <span x-show="removeLogo" class="text-xs text-red-500 font-medium mt-2 block">Akan dihapus saat simpan</span>
                        @endif
                    </div>
                </div>
                {{-- Favicon --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 font-jakarta">Favicon <span class="text-[10px] font-normal text-slate-500">ICO/PNG, maks 512KB</span></label>
                    <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 hover:border-violet-500/50 rounded-2xl p-4 text-center transition-colors bg-gray-50/50 dark:bg-slate-950/20">
                        <template x-if="faviconPreview && !removeFavicon">
                            <img :src="faviconPreview" class="w-10 h-10 rounded-lg object-cover mx-auto mb-2 shadow border border-slate-700">
                        </template>
                        <template x-if="!faviconPreview || removeFavicon">
                            <div class="w-10 h-10 bg-gray-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-gray-400 dark:text-slate-500 mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                            </div>
                        </template>
                        <label class="cursor-pointer block">
                            <span class="text-xs font-bold text-violet-400 hover:text-violet-300 transition-colors">Pilih favicon</span>
                            <input type="file" name="favicon" accept="image/*,.ico" class="hidden" @change="onFaviconChange($event)">
                        </label>
                        @if($faviconUrl)
                        <input type="hidden" name="remove_favicon" :value="removeFavicon ? '1' : '0'">
                        <button type="button" @click="removeFavicon=true;faviconPreview=''" x-show="!removeFavicon"
                            class="text-xs text-red-400 hover:text-red-300 font-semibold mt-2 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Favicon
                        </button>
                        <span x-show="removeFavicon" class="text-xs text-red-500 font-medium mt-2 block">Akan dihapus saat simpan</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nama & Tagline --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Nama Restoran <span class="text-red-500">*</span></label>
                    <input type="text" name="restaurant_name" value="{{ $s['restaurant_name'] ?? 'Restoran' }}"
                        @input="document.getElementById('prev-name').textContent=$event.target.value"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-1.5 font-jakarta">Tagline <span class="text-red-500">*</span></label>
                    <input type="text" name="restaurant_tagline" value="{{ $s['restaurant_tagline'] ?? 'NUSANTARA' }}"
                        @input="document.getElementById('prev-tag').textContent=$event.target.value"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" required>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-1.5 font-jakarta">Deskripsi Singkat</label>
                    <textarea name="description" rows="2"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none resize-none"
                    placeholder="Deskripsi untuk SEO dan footer">{{ $s['description'] ?? '' }}</textarea>
            </div>

            {{-- Kontak --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="12" r="1.5"/></svg>
                        Alamat
                    </label>
                    <input type="text" name="address" value="{{ $s['address'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" placeholder="Jl. Kuliner No. 1">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-1.5 font-jakarta inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Telepon
                    </label>
                    <input type="text" name="phone" value="{{ $s['phone'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" placeholder="+62 812-xxxx-xxxx">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-1.5 font-jakarta inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Email
                    </label>
                    <input type="email" name="email" value="{{ $s['email'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none" placeholder="info@restoran.id">
                </div>
            </div>

            {{-- Warna --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 7.5h-.01m6 0h-.01m0 4h-.01m2.28-5.58a9 9 0 101.22 1.22l-1.22-1.22z"/></svg>
                    Warna Utama
                </label>
                <div class="flex items-center gap-3">
                    <input type="color" name="primary_color" value="{{ $s['primary_color'] ?? '#f97316' }}"
                        id="colorPicker" class="w-12 h-10 rounded-lg border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 cursor-pointer p-1">
                    <input type="text" id="colorText" value="{{ $s['primary_color'] ?? '#f97316' }}"
                        class="w-28 px-3 py-2 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-xs font-mono text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-violet-500 outline-none" readonly>
                    <p class="text-[11px] text-slate-500 font-medium">Warna aksen di seluruh tampilan frontend</p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-white/[0.06]">
                <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-violet-600/30 uppercase tracking-wider font-jakarta">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
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
        showVideoInput: @json(!empty($s['home_video_url'] ?? '')),
        onImg(field, e) {
            const f = e.target.files[0];
            if (f) this[field+'Preview'] = URL.createObjectURL(f);
        }
    }">
    @csrf
    <div class="bg-white dark:bg-admin-card rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-white/[0.07]">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-white/[0.06] bg-gradient-to-r from-orange-50 dark:from-orange-950/20 to-amber-50 dark:to-amber-950/20">
            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center text-white shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-sm">Tampilan Halaman Home</h3>
                <p class="text-xs text-gray-500 dark:text-slate-500 font-medium">Gambar latar, judul hero, teks, dan foto dekorasi</p>
            </div>
        </div>
        <div class="p-6 space-y-6">

            {{-- Teks Hero --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">
                        Teks Badge <span class="text-[10px] font-normal text-slate-500 font-sans">(strip kecil di atas judul)</span>
                    </label>
                    <input type="text" name="home_badge_text" value="{{ $s['home_badge_text'] ?? 'Buka Sekarang · Estimasi 15-30 menit' }}"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-orange-500 outline-none"
                        placeholder="Buka Sekarang · Estimasi 15-30 menit">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-1.5 font-jakarta">
                        Judul Hero <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="home_hero_title" value="{{ $s['home_hero_title'] ?? 'Cita Rasa Nusantara di Ujung Jari' }}"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-orange-500 outline-none"
                        placeholder="Judul besar di halaman utama" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-1.5 font-jakarta">
                        Sub-judul / Deskripsi Hero <span class="text-red-500">*</span>
                    </label>
                    <textarea name="home_hero_subtitle" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-orange-500 outline-none resize-none"
                        placeholder="Teks deskripsi di bawah judul hero" required>{{ $s['home_hero_subtitle'] ?? '' }}</textarea>
                </div>
            </div>

            {{-- Gambar Latar Belakang --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 font-jakarta">
                    Gambar Latar Belakang Hero
                    <span class="text-[10px] font-normal text-slate-500 font-sans ml-1">Upload file atau masukkan URL</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Preview --}}
                    <div class="relative rounded-2xl overflow-hidden h-36 bg-slate-950 border border-slate-800">
                        <img :src="bgPreview || 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=200&fit=crop'"
                            class="w-full h-full object-cover" alt="Preview BG">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <span class="text-white text-xs font-bold bg-slate-900/80 px-3 py-1 rounded-full border border-slate-700 font-jakarta uppercase tracking-wider text-[10px]">Preview Latar</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-700 hover:border-orange-500/50 rounded-xl p-4 cursor-pointer transition-colors bg-slate-950/20">
                            <svg class="w-6 h-6 text-slate-500 mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span class="text-xs font-bold text-orange-400">Upload gambar</span>
                            <span class="text-[10px] text-slate-500 mt-0.5 font-medium">JPG/PNG/WEBP, maks 5MB</span>
                            <input type="file" name="home_bg_image" accept="image/*" class="hidden"
                                @change="onImg('bg', $event)">
                        </label>
                        <div>
                            <input type="url" name="home_bg_image_url"
                                    value="{{ str_starts_with($s['home_bg_image'] ?? '', 'http') ? $s['home_bg_image'] : '' }}"
                                    @input="bgPreview = $event.target.value"
                                    class="w-full px-3 py-2 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-xs text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-orange-500 outline-none"
                                    placeholder="Atau masukkan URL gambar...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3 Foto Dekorasi Melayang --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 font-jakarta">
                    Foto Dekorasi Melayang (kanan hero)
                    <span class="text-[10px] font-normal text-slate-500 font-sans ml-1">3 foto bulat di sisi kanan</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach([
                        ['key'=>'home_float_img1', 'label'=>'Foto 1 (Besar)', 'xvar'=>'f1'],
                        ['key'=>'home_float_img2', 'label'=>'Foto 2 (Kecil)', 'xvar'=>'f2'],
                        ['key'=>'home_float_img3', 'label'=>'Foto 3 (Tengah)', 'xvar'=>'f3'],
                    ] as $img)
                    <div class="space-y-2">
                        <p class="text-[11px] font-bold text-slate-400 font-jakarta">{{ $img['label'] }}</p>
                        <div class="relative rounded-xl overflow-hidden h-24 bg-slate-950 border border-slate-800">
                            <img :src="{{ $img['xvar'] }}Preview || '{{ $s[$img['key']] ?? '' }}'"
                                class="w-full h-full object-cover" alt="{{ $img['label'] }}">
                        </div>
                        <label class="flex items-center justify-center gap-1.5 border border-dashed border-slate-700 hover:border-orange-500/50 rounded-xl py-2 cursor-pointer transition-colors bg-slate-950/20">
                            <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span class="text-xs font-bold text-orange-400">Upload</span>
                            <input type="file" name="{{ $img['key'] }}" accept="image/*" class="hidden"
                                @change="onImg('{{ $img['xvar'] }}', $event)">
                        </label>
                        <input type="url" name="{{ $img['key'] }}_url"
                        value="{{ str_starts_with($s[$img['key']] ?? '', 'http') ? $s[$img['key']] : '' }}"
                        @input="{{ $img['xvar'] }}Preview = $event.target.value"
                        class="w-full px-3 py-1.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-xs text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-orange-500 outline-none"
                        placeholder="URL gambar...">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-white/[0.06]">
                <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-orange-500/30 uppercase tracking-wider font-jakarta">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan Tampilan Home
                </button>
            </div>
        </div>
    </div>
</form>
</div>

{{-- ===== INFO SISTEM ===== --}}
<div class="bg-white dark:bg-admin-card rounded-2xl p-5 border border-gray-200 dark:border-white/[0.07]">
    <h3 class="font-bold text-gray-900 dark:text-slate-100 mb-4 flex items-center gap-2 text-xs uppercase tracking-wider font-jakarta">
        <span class="w-6 h-6 bg-violet-100 dark:bg-violet-950/30 rounded-lg flex items-center justify-center border border-violet-200 dark:border-violet-500/10 text-violet-600 dark:text-violet-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </span>
        Informasi Sistem
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        @foreach($sysInfo as $info)
        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-950/40 border border-gray-200 dark:border-slate-800/80 rounded-xl">
            <span class="text-xs text-gray-500 dark:text-slate-500 font-medium">{{ $info['label'] }}</span>
            <span class="text-xs font-bold text-gray-700 dark:text-slate-300 font-mono">{{ $info['value'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== STATISTIK DB ===== --}}
<div class="bg-white dark:bg-admin-card rounded-2xl p-5 border border-gray-200 dark:border-white/[0.07]">
    <h3 class="font-bold text-gray-900 dark:text-slate-100 mb-4 flex items-center gap-2 text-xs uppercase tracking-wider font-jakarta">
        <span class="w-6 h-6 bg-emerald-100 dark:bg-emerald-950/30 rounded-lg flex items-center justify-center border border-emerald-200 dark:border-emerald-500/10 text-emerald-600 dark:text-emerald-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
        </span>
        Statistik Database
    </h3>
    <div class="grid grid-cols-3 sm:grid-cols-3 gap-4">
        @foreach($dbStats as $stat)
        <div class="p-4 bg-gray-50 dark:bg-slate-950/40 border border-gray-200 dark:border-slate-800/80 rounded-xl text-center flex flex-col items-center justify-center relative overflow-hidden">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-slate-400 mb-2 border border-gray-200 dark:border-slate-800">
                @include('admin.partials.icon', ['name' => $stat['icon'], 'active' => false])
            </div>
            <div class="text-xl font-bold text-gray-900 dark:text-slate-100 font-jakarta">{{ $stat['value'] }}</div>
            <div class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mt-1 font-jakarta">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cp = document.getElementById('colorPicker');
    const ct = document.getElementById('colorText');
    if (cp && ct) cp.addEventListener('input', () => ct.value = cp.value);
});
</script>
@endsection
