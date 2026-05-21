@extends('admin.layouts.app')
@section('title','Cerita Kami & Tim')
@section('page-title','Cerita Kami & Tim')
@section('page-subtitle','Kelola konten halaman tentang restoran')
@section('content')

@include('admin.partials.flash')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

{{-- ===== CERITA KAMI ===== --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-gray-900 dark:text-white">📖 Cerita Kami</h3>
        <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Ubah teks, angka statistik, dan gambar</p>
    </div>
    <form method="POST" action="{{ route('admin.content.about.update') }}" enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf

        {{-- Gambar --}}
        <div x-data="{previewUrl:'{{ $about->image_src ?? '' }}',
            handleFile(f){ if(!f)return; const r=new FileReader(); r.onload=e=>{this.previewUrl=e.target.result}; r.readAsDataURL(f); }
        }">
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Foto Restoran</label>
            <div x-show="previewUrl" class="relative mb-3 rounded-xl overflow-hidden h-40 group">
                <img :src="previewUrl" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button type="button" @click="$refs.fi.click()" class="bg-white text-gray-900 text-xs font-semibold px-4 py-2 rounded-xl">📷 Ganti</button>
                </div>
            </div>
            <div x-show="!previewUrl" @click="$refs.fi.click()" class="border-2 border-dashed border-gray-300 dark:border-slate-600 hover:border-violet-400 rounded-xl p-5 text-center cursor-pointer transition-all">
                <div class="text-3xl mb-2">🖼️</div>
                <p class="text-sm text-gray-500 dark:text-slate-400">Klik untuk pilih gambar</p>
            </div>
            <input x-ref="fi" type="file" name="image" accept="image/*" class="hidden" @change="handleFile($event.target.files[0])">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Atau URL Gambar</label>
            <input type="url" name="image_url" value="{{ $about->image_url }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ $about->title }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Paragraf 1</label>
            <textarea name="description_1" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none resize-none">{{ $about->description_1 }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Paragraf 2</label>
            <textarea name="description_2" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none resize-none">{{ $about->description_2 }}</textarea>
        </div>

        {{-- Stats --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Statistik (4 angka)</label>
            <div class="grid grid-cols-2 gap-3">
                @php $stats = $about->stats ?? [['value'=>'2019','label'=>'Tahun Berdiri'],['value'=>'10K+','label'=>'Pelanggan Puas'],['value'=>'500+','label'=>'Menu Tersedia'],['value'=>'4.9★','label'=>'Rating Google']]; @endphp
                @foreach($stats as $i => $stat)
                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-xl p-3">
                    <input type="text" name="stats[{{ $i }}][value]" value="{{ $stat['value'] }}" placeholder="2019" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm font-bold focus:ring-2 focus:ring-violet-500 outline-none mb-1.5">
                    <input type="text" name="stats[{{ $i }}][label]" value="{{ $stat['label'] }}" placeholder="Tahun Berdiri" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-500 dark:text-slate-400 text-xs focus:ring-2 focus:ring-violet-500 outline-none">
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all shadow-lg shadow-violet-600/30 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan Perubahan
        </button>
    </form>
</div>

{{-- Tim dipisah ke halaman terpisah --}}

</div>

<script>
document.addEventListener('click', function() {
    const form = document.getElementById('teamEditForm');
    const el = document.querySelector('[x-data*="editMember"]');
    if (!form || !el) return;
    const m = Alpine.evaluate(el, 'editMember');
    if (m) form.action = `/admin/content/team/${m.id}`;
});
</script>
@endsection
