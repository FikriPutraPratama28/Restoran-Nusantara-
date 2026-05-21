@extends('admin.layouts.app')
@section('title','Galeri - Momen Bersama')
@section('page-title','Galeri')
@section('page-subtitle','Kelola Momen Bersama (Galeri)')
@section('content')

@include('admin.partials.flash')

<div x-data="{showModal:false,showDelete:false,isEdit:false,deleteId:null,editMember:null,
    openAdd(){ this.isEdit=false; this.editMember=null; this.showModal=true; },
    openEdit(i){ this.isEdit=true; this.editMember=i; this.showModal=true; },
    confirmDelete(id){ this.deleteId=id; this.showDelete=true; }
}">

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500 dark:text-slate-400">{{ $images->count() }} item</p>
        <button @click="openAdd()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-all shadow-lg shadow-violet-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Foto
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($images as $img)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden group">
            <div class="relative h-44 overflow-hidden cursor-pointer" @click="openEdit({{ $img->toJson() }})">
                <img src="{{ $img->image ? asset('storage/'.$img->image) : $img->image_url }}" alt="{{ $img->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                    <div>
                        <div class="text-lg font-bold text-white line-clamp-1">{{ $img->title }}</div>
                        <div class="text-xs text-white/80 line-clamp-2">{{ $img->caption }}</div>
                    </div>
                </div>
                <div class="absolute top-3 right-3 flex gap-2">
                    <span class="text-xs {{ $img->is_active ? 'bg-emerald-500' : 'bg-gray-500' }} text-white px-2 py-0.5 rounded-full font-semibold">
                        {{ $img->is_active ? 'Aktif' : 'Off' }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                <div class="flex gap-2">
                    <button @click="openEdit({{ $img->toJson() }})" class="flex-1 text-xs py-2 bg-primary-50 text-primary-700 rounded-lg hover:bg-primary-100 transition-all font-medium">✏️ Edit</button>
                    <button @click="confirmDelete({{ $img->id }})" class="flex-1 text-xs py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all font-medium">🗑️ Hapus</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-gray-300 dark:border-slate-700">
            <div class="text-5xl mb-3">📸</div>
            <div class="text-xl font-semibold text-gray-700 dark:text-white mb-2">Belum ada foto Momen Bersama</div>
            <p class="text-sm text-gray-500 dark:text-slate-400">Tambah konten menggunakan tombol Tambah Foto di atas.</p>
        </div>
        @endforelse
    </div>

    {{-- Modal Add/Edit --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="showModal=false"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-lg p-6 z-10">
            <h3 class="font-bold mb-4" x-text="isEdit ? 'Edit Foto' : 'Tambah Foto'"></h3>
            <form x-show="!isEdit" method="POST" action="{{ route('admin.content.gallery.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.partials.gallery-form')
                <div class="mt-4 flex gap-2">
                    <button type="button" @click="showModal=false" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            <form x-show="isEdit" id="galleryEditForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf @method('PUT')
                @include('admin.partials.gallery-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold transition-all">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDelete" x-cloak class="fixed inset-0 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="showDelete=false"></div>
        <div class="relative bg-white rounded-2xl p-6 z-10 w-full max-w-sm text-center">
            <h3 class="font-bold mb-2">Hapus foto?</h3>
            <p class="text-sm text-gray-500 mb-4">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-2">
                <button @click="showDelete=false" class="btn btn-outline flex-1">Batal</button>
                <form :action="`/admin/content/gallery/${deleteId}`" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full">Hapus</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('click', function(){
    const form = document.getElementById('galleryEditForm');
    const el = document.querySelector('[x-data*="editMember"]');
    if (!form || !el) return;
    const item = Alpine.evaluate(el, 'editMember');
    if (item) form.action = `/admin/content/gallery/${item.id}`;
});
</script>

@endsection
