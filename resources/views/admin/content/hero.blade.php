@extends('admin.layouts.app')
@section('title','Hero / Banner')
@section('page-title','Hero & Banner')
@section('page-subtitle','Kelola gambar/video utama halaman depan')
@section('content')

@include('admin.partials.flash')

<div x-data="{showModal:false, showDeleteModal:false, isEdit:false, deleteId:null, editSlide:null,
    openAdd(){ this.isEdit=false; this.editSlide=null; this.showModal=true; },
    openEdit(s){ this.isEdit=true; this.editSlide=s; this.showModal=true; },
    confirmDelete(id){ this.deleteId=id; this.showDeleteModal=true; }
}">

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-500 dark:text-slate-400">{{ $slides->count() }} slide terdaftar</p>
    </div>
    <button @click="openAdd()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-all shadow-lg shadow-violet-600/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Slide
    </button>
</div>

{{-- Slides Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($slides as $slide)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden group">
        <div class="relative h-44 overflow-hidden">
            @if($slide->media_type === 'video' && $slide->video_url)
                <video src="{{ $slide->video_url }}" class="w-full h-full object-cover" muted loop></video>
            @else
                <img src="{{ $slide->image_src }}" alt="{{ $slide->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
                <div>
                    <h3 class="text-white font-bold text-sm">{{ $slide->title }}</h3>
                    <p class="text-white/60 text-xs">{{ $slide->subtitle }}</p>
                </div>
            </div>
            <div class="absolute top-3 right-3 flex gap-2">
                <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $slide->is_active ? 'bg-emerald-500 text-white' : 'bg-gray-500 text-white' }}">
                    {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="text-xs px-2 py-1 rounded-full bg-blue-500 text-white font-semibold">{{ ucfirst($slide->media_type) }}</span>
            </div>
        </div>
        <div class="p-4 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-slate-400">
                CTA: <span class="font-medium text-gray-800 dark:text-slate-200">{{ $slide->cta_text }}</span>
            </div>
            <div class="flex gap-2">
                <button @click="openEdit({{ $slide->toJson() }})"
                    class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/20 text-violet-600 flex items-center justify-center hover:bg-violet-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button @click="confirmDelete({{ $slide->id }})"
                    class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center hover:bg-red-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-2 text-center py-16 bg-white dark:bg-admin-card rounded-2xl border border-gray-200 dark:border-slate-700/50">
        <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-200 dark:border-slate-700/30 text-gray-400 dark:text-slate-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-gray-400 dark:text-slate-400">Belum ada slide. Tambahkan slide pertama!</p>
    </div>
    @endforelse
</div>

{{-- ADD/EDIT MODAL --}}
<div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-xl z-10 max-h-[90vh] flex flex-col"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex-shrink-0">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg" x-text="isEdit ? 'Edit Slide' : 'Tambah Slide Baru'"></h3>
            <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="overflow-y-auto flex-1">
            {{-- ADD FORM --}}
            <form x-show="!isEdit" method="POST" action="{{ route('admin.content.hero.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @include('admin.partials.hero-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all">Simpan</button>
                </div>
            </form>
            {{-- EDIT FORM --}}
            <form x-show="isEdit" id="heroEditForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf @method('PUT')
                @include('admin.partials.hero-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6 text-center">
        <div class="w-14 h-14 bg-red-500/10 dark:bg-red-900/20 text-red-500 dark:text-red-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-500/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Hapus Slide?</h3>
        <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">Slide ini akan dihapus permanen.</p>
        <div class="flex gap-3">
            <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
            <form :action="`/admin/content/hero/${deleteId}`" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    document.addEventListener('click', function() {
        const form = document.getElementById('heroEditForm');
        const slide = Alpine.evaluate(document.querySelector('[x-data]'), 'editSlide');
        if (form && slide) form.action = `/admin/content/hero/${slide.id}`;
    });
});
</script>
@endsection
