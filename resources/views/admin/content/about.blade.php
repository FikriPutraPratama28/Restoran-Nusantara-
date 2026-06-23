@extends('admin.layouts.app')
@section('title','Cerita Kami')
@section('page-title','Cerita Kami')
@section('page-subtitle','Kelola konten tentang restoran')
@section('content')

@include('admin.partials.flash')

<form method="POST" action="{{ route('admin.content.about.update') }}" enctype="multipart/form-data">
@csrf

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ===== Kolom Kiri: Form ===== --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Card: Teks Utama --}}
        <div class="bg-white dark:bg-admin-card rounded-2xl border border-gray-200 dark:border-white/[0.07] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.06] flex items-center gap-2">
                <div class="w-7 h-7 bg-violet-950/30 rounded-lg flex items-center justify-center border border-violet-500/10 text-violet-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-sm uppercase tracking-wider">Teks Cerita</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Badge (Tentang Kami)</label>
                        <input type="text" name="badge" value="{{ $about->badge ?? 'Tentang Kami' }}"
                            class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"
                            placeholder="Tentang Kami">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Sub-judul (Kisah Kami)</label>
                        <input type="text" name="subtitle" value="{{ $about->subtitle ?? 'Kisah Kami' }}"
                            class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"
                            placeholder="Kisah Kami">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Judul Utama <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ $about->title }}" required
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"
                        placeholder="Berawal dari Cinta terhadap Kuliner">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Tagline / Deskripsi Singkat</label>
                    <input type="text" name="tagline" value="{{ $about->tagline ?? 'Dari dapur keluarga ke rumah makan digital modern' }}"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"
                        placeholder="Dari dapur keluarga ke rumah makan digital modern">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Paragraf 1</label>
                    <textarea name="description_1" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none resize-none">{{ $about->description_1 }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Paragraf 2</label>
                    <textarea name="description_2" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none resize-none">{{ $about->description_2 }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Label Badge Chef</label>
                        <input type="text" name="chef_label" value="{{ $about->chef_label ?? 'Chef Berpengalaman' }}"
                            class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"
                            placeholder="Chef Berpengalaman">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Sub Label Chef</label>
                        <input type="text" name="chef_sub" value="{{ $about->chef_sub ?? '15+ tahun pengalaman' }}"
                            class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"
                            placeholder="15+ tahun pengalaman">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Statistik --}}
        <div class="bg-white dark:bg-admin-card rounded-2xl border border-gray-200 dark:border-white/[0.07] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.06] flex items-center gap-2">
                <div class="w-7 h-7 bg-emerald-950/30 rounded-lg flex items-center justify-center border border-emerald-500/10 text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-sm uppercase tracking-wider">Statistik (4 Angka)</h3>
            </div>
            <div class="p-6">
                @php $stats = $about->stats ?? [['value'=>'2019','label'=>'Tahun Berdiri'],['value'=>'10K+','label'=>'Pelanggan Puas'],['value'=>'500+','label'=>'Menu Tersedia'],['value'=>'4.9★','label'=>'Rating Google']]; @endphp
                <div class="grid grid-cols-2 gap-3">
                    @foreach($stats as $i => $stat)
                    <div class="bg-gray-50 dark:bg-slate-950/40 border border-gray-200 dark:border-slate-800/80 rounded-xl p-4">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 font-jakarta">Statistik {{ $i + 1 }}</label>
                        <input type="text" name="stats[{{ $i }}][value]" value="{{ $stat['value'] }}" placeholder="2019"
                            class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-base font-extrabold text-primary-600 dark:text-primary-400 focus:ring-2 focus:ring-violet-500 outline-none mb-2">
                        <input type="text" name="stats[{{ $i }}][label]" value="{{ $stat['label'] }}" placeholder="Tahun Berdiri"
                            class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-xs text-gray-500 dark:text-slate-500 focus:ring-2 focus:ring-violet-500 outline-none">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Kolom Kanan: Foto + Simpan ===== --}}
    <div class="space-y-5">

        {{-- Card: Foto --}}
        <div class="bg-white dark:bg-admin-card rounded-2xl border border-gray-200 dark:border-white/[0.07] overflow-hidden"
            x-data="{
                previewUrl: '{{ addslashes($about->image_src ?? '') }}',
                handleFile(f) {
                    if (!f) return;
                    const r = new FileReader();
                    r.onload = e => { this.previewUrl = e.target.result; };
                    r.readAsDataURL(f);
                }
            }">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.06] flex items-center gap-2">
                <div class="w-7 h-7 bg-blue-950/30 rounded-lg flex items-center justify-center border border-blue-500/10 text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-sm uppercase tracking-wider">Foto Restoran</h3>
            </div>
            <div class="p-5 space-y-4">
                {{-- Preview --}}
                <div x-show="previewUrl" class="relative rounded-xl overflow-hidden h-48 group border border-gray-200 dark:border-slate-700">
                    <img :src="previewUrl" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <button type="button" @click="$refs.fi.click()"
                            class="bg-white text-gray-900 text-xs font-bold px-4 py-2 rounded-xl uppercase tracking-wider font-jakarta flex items-center gap-1.5 shadow-lg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
                            Ganti
                        </button>
                        <button type="button" @click="previewUrl=''"
                            class="bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-xl uppercase tracking-wider font-jakarta flex items-center gap-1.5 shadow-lg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </div>
                </div>

                {{-- Drop zone --}}
                <div x-show="!previewUrl"
                    @click="$refs.fi.click()"
                    class="border-2 border-dashed border-gray-200 dark:border-slate-700 hover:border-violet-500/50 rounded-xl p-8 text-center cursor-pointer transition-all bg-gray-50/50 dark:bg-slate-950/20">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-3 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-slate-400 mb-1">Klik untuk upload foto</p>
                    <p class="text-xs text-gray-400">JPG, PNG, WEBP — Maks. 10MB</p>
                </div>
                <input x-ref="fi" type="file" name="image" accept="image/*" class="hidden" x-on:change="handleFile($event.target.files[0])">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Atau URL Gambar</label>
                    <input type="url" name="image_url" value="{{ $about->image_url }}"
                        placeholder="https://..."
                        class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                </div>
            </div>
        </div>

        {{-- Preview Mini --}}
        <div class="bg-white dark:bg-admin-card rounded-2xl border border-gray-200 dark:border-white/[0.07] overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 dark:border-white/[0.06]">
                <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-xs uppercase tracking-wider">Info Saat Ini</h3>
            </div>
            <div class="p-5 space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-slate-400 text-xs">Judul</span>
                    <span class="font-semibold text-gray-900 dark:text-white text-xs text-right max-w-[160px] truncate">{{ $about->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-slate-400 text-xs">Subtitle</span>
                    <span class="font-semibold text-gray-900 dark:text-white text-xs">{{ $about->subtitle ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-slate-400 text-xs">Foto</span>
                    <span class="text-xs {{ $about->image ? 'text-emerald-500' : ($about->image_url ? 'text-blue-400' : 'text-red-400') }}">
                        {{ $about->image ? 'Upload' : ($about->image_url ? 'URL' : 'Belum ada') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-slate-400 text-xs">Statistik</span>
                    <span class="font-semibold text-gray-900 dark:text-white text-xs">{{ count($about->stats ?? []) }} item</span>
                </div>
                <div class="pt-2 border-t border-gray-100 dark:border-slate-700">
                    <a href="{{ route('home') }}#tentang" target="_blank"
                        class="flex items-center justify-center gap-2 w-full py-2 rounded-xl text-xs font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Lihat di Website
                    </a>
                </div>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <button type="submit"
            class="w-full py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-sm uppercase tracking-wider transition-all shadow-lg shadow-violet-600/20 font-jakarta flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            Simpan Semua Perubahan
        </button>
    </div>

</div>
</form>
@endsection
