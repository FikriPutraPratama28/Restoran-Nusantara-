@extends('admin.layouts.app')
@section('title','Fasilitas')
@section('page-title','Fasilitas Restoran')
@section('page-subtitle','Kelola foto dan informasi fasilitas')
@section('content')

@include('admin.partials.flash')

<div x-data="{showModal:false,showDeleteModal:false,isEdit:false,deleteId:null,editFacility:null,lightboxOpen:false,lightboxImg:'',
    openAdd(){ this.isEdit=false; this.editFacility=null; this.showModal=true; },
    openEdit(f){ this.isEdit=true; this.editFacility=f; this.showModal=true; },
    confirmDelete(id){ this.deleteId=id; this.showDeleteModal=true; },
    openLightbox(img){ this.lightboxImg=img; this.lightboxOpen=true; }
}">

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $facilities->count() }} fasilitas terdaftar</p>
    <button @click="openAdd()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-all shadow-lg shadow-violet-600/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Fasilitas
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($facilities as $facility)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden group">
        <div class="relative h-40 overflow-hidden cursor-pointer" @click="openLightbox('{{ $facility->image_src }}')">
            <img src="{{ $facility->image_src }}" alt="{{ $facility->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                <div class="flex items-center gap-2">
                    <span class="text-xl">{{ $facility->icon }}</span>
                    <span class="text-white font-bold text-sm">{{ $facility->title }}</span>
                </div>
            </div>
            <div class="absolute top-2 right-2 flex gap-1">
                @if($facility->tag)
                <span class="text-xs bg-white/20 backdrop-blur-sm text-white px-2 py-0.5 rounded-full font-semibold border border-white/30">{{ $facility->tag }}</span>
                @endif
                <span class="text-xs {{ $facility->is_active ? 'bg-emerald-500' : 'bg-gray-500' }} text-white px-2 py-0.5 rounded-full font-semibold">
                    {{ $facility->is_active ? 'Aktif' : 'Off' }}
                </span>
            </div>
            {{-- Zoom icon --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                </div>
            </div>
        </div>
        <div class="p-4">
            <p class="text-xs text-gray-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $facility->description }}</p>
            <div class="flex gap-2">
                <button @click="openEdit({{ $facility->toJson() }})" class="flex-1 text-xs py-1.5 bg-violet-50 dark:bg-violet-900/20 text-violet-600 rounded-lg hover:bg-violet-100 transition-all font-medium">✏️ Edit</button>
                <button @click="confirmDelete({{ $facility->id }})" class="flex-1 text-xs py-1.5 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-lg hover:bg-red-100 transition-all font-medium">🗑️ Hapus</button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700">
        <div class="text-5xl mb-3">🏢</div>
        <p class="text-gray-500 dark:text-slate-400">Belum ada fasilitas. Tambahkan fasilitas pertama!</p>
    </div>
    @endforelse
</div>

{{-- Lightbox --}}
<div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    @click="lightboxOpen=false">
    <button class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img :src="lightboxImg" class="max-w-4xl max-h-[85vh] object-contain rounded-xl shadow-2xl" @click.stop>
</div>

{{-- ADD/EDIT MODAL --}}
<div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg z-10 max-h-[90vh] flex flex-col"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex-shrink-0">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg" x-text="isEdit ? 'Edit Fasilitas' : 'Tambah Fasilitas'"></h3>
            <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="overflow-y-auto flex-1">
            <form x-show="!isEdit" method="POST" action="{{ route('admin.content.facility.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @include('admin.partials.facility-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all">Simpan</button>
                </div>
            </form>
            <form x-show="isEdit" id="facilityEditForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf @method('PUT')
                @include('admin.partials.facility-form')
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
        <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">🗑️</div>
        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Hapus Fasilitas?</h3>
        <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">Data fasilitas akan dihapus permanen.</p>
        <div class="flex gap-3">
            <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
            <form :action="`/admin/content/facility/${deleteId}`" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>

</div>

<script>
document.addEventListener('click', function() {
    const form = document.getElementById('facilityEditForm');
    const el = document.querySelector('[x-data*="editFacility"]');
    if (!form || !el) return;
    const f = Alpine.evaluate(el, 'editFacility');
    if (f) form.action = `/admin/content/facility/${f.id}`;
});
</script>
@endsection
