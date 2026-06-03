@extends('admin.layouts.app')
@section('title','Cerita Kami & Tim')
@section('page-title','Cerita Kami & Tim')
@section('page-subtitle','Kelola konten tentang restoran')
@section('content')

@include('admin.partials.flash')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

{{-- ===== CERITA KAMI ===== --}}
<div class="bg-white dark:bg-admin-card rounded-2xl overflow-hidden border border-gray-200 dark:border-white/[0.07]">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.06] flex items-center gap-2">
        <div class="w-7 h-7 bg-violet-950/30 rounded-lg flex items-center justify-center border border-violet-500/10 text-violet-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-sm uppercase tracking-wider">Cerita Kami</h3>
    </div>
    <form method="POST" action="{{ route('admin.content.about.update') }}" enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf

        {{-- Gambar --}}
        <div x-data="{previewUrl:'{{ $about->image_src ?? '' }}',
            handleFile(f){ if(!f)return; const r=new FileReader(); r.onload=e=>{this.previewUrl=e.target.result}; r.readAsDataURL(f); }
        }">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 font-jakarta">Foto Restoran</label>
            <div x-show="previewUrl" class="relative mb-3 rounded-xl overflow-hidden h-40 group border border-slate-800">
                <img :src="previewUrl" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button type="button" @click="$refs.fi.click()" class="bg-white text-gray-900 text-[11px] font-bold px-4 py-2 rounded-xl uppercase tracking-wider font-jakarta flex items-center gap-1.5 shadow-lg">
                        <svg class="w-3.5 h-3.5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <circle cx="12" cy="13" r="3"/>
                        </svg>
                        Ganti Foto
                    </button>
                </div>
            </div>
            <div x-show="!previewUrl" @click="$refs.fi.click()" class="border-2 border-dashed border-gray-200 dark:border-slate-700 hover:border-violet-500/50 rounded-xl p-5 text-center cursor-pointer transition-all bg-gray-50/50 dark:bg-slate-950/20">
                <div class="w-10 h-10 bg-gray-100 dark:bg-slate-800 rounded-lg flex items-center justify-center mx-auto mb-2 text-gray-400 dark:text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400 dark:text-slate-400 font-bold uppercase tracking-wider font-jakarta">Klik untuk pilih gambar</p>
            </div>
            <input x-ref="fi" type="file" name="image" accept="image/*" class="hidden" @change="handleFile($event.target.files[0])">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Atau URL Gambar</label>
            <input type="url" name="image_url" value="{{ $about->image_url }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ $about->title }}" required class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Paragraf 1</label>
            <textarea name="description_1" rows="3" class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none resize-none font-medium">{{ $about->description_1 }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-jakarta">Paragraf 2</label>
            <textarea name="description_2" rows="3" class="w-full px-4 py-2.5 rounded-xl border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none resize-none font-medium">{{ $about->description_2 }}</textarea>
        </div>

        {{-- Stats --}}
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 font-jakarta">Statistik (4 angka)</label>
            <div class="grid grid-cols-2 gap-3">
                @php $stats = $about->stats ?? [['value'=>'2019','label'=>'Tahun Berdiri'],['value'=>'10K+','label'=>'Pelanggan Puas'],['value'=>'500+','label'=>'Menu Tersedia'],['value'=>'4.9★','label'=>'Rating Google']]; @endphp
                @foreach($stats as $i => $stat)
                <div class="bg-gray-50 dark:bg-slate-950/40 border border-gray-200 dark:border-slate-800/80 rounded-xl p-3">
                    <input type="text" name="stats[{{ $i }}][value]" value="{{ $stat['value'] }}" placeholder="2019" class="w-full px-3 py-1.5 rounded-lg border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none mb-1.5">
                    <input type="text" name="stats[{{ $i }}][label]" value="{{ $stat['label'] }}" placeholder="Tahun Berdiri" class="w-full px-3 py-1.5 rounded-lg border bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-500 text-xs focus:ring-2 focus:ring-violet-500 outline-none">
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-lg shadow-violet-600/30 flex items-center justify-center gap-2 font-jakarta">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            Simpan Perubahan
        </button>
    </form>
</div>

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
