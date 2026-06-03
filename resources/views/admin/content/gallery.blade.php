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
        <p class="text-sm text-slate-500 font-medium font-jakarta">{{ $images->count() }} item</p>
        <button @click="openAdd()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-violet-600/20 uppercase tracking-wider font-jakarta">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Foto
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($images as $img)
        <div class="bg-white dark:bg-admin-card rounded-2xl overflow-hidden group border border-gray-200 dark:border-white/[0.07]">
            <div class="relative h-44 overflow-hidden cursor-pointer" @click="openEdit({{ $img->toJson() }})">
                <img src="{{ $img->image ? asset('storage/'.$img->image) : $img->image_url }}" alt="{{ $img->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-4">
                    <div>
                        <div class="text-[15px] font-bold text-white font-jakarta line-clamp-1">{{ $img->title }}</div>
                        <div class="text-[11px] text-white/70 line-clamp-2 mt-1">{{ $img->caption }}</div>
                    </div>
                </div>
                <div class="absolute top-3 right-3 flex gap-2">
                    <span class="text-[10px] {{ $img->is_active ? 'bg-emerald-500' : 'bg-slate-600' }} text-white px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider font-jakarta">
                        {{ $img->is_active ? 'Aktif' : 'Off' }}
                    </span>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-white/[0.06]">
                <div class="flex gap-2">
                    <button @click="openEdit({{ $img->toJson() }})" class="flex-1 text-xs py-2 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-700 transition-all font-bold uppercase tracking-wider font-jakarta flex items-center justify-center gap-1.5 border border-gray-200 dark:border-slate-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Edit
                    </button>
                    <button @click="confirmDelete({{ $img->id }})" class="flex-1 text-xs py-2 bg-red-950/30 text-red-400 rounded-lg hover:bg-red-900/20 transition-all font-bold uppercase tracking-wider font-jakarta flex items-center justify-center gap-1.5 border border-red-500/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-white dark:bg-admin-card rounded-2xl border border-gray-200 dark:border-white/[0.07]">
            <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-200 dark:border-white/[0.06]">
                <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-1 font-jakarta uppercase tracking-wider">Belum ada foto Momen Bersama</div>
            <p class="text-xs text-gray-400 dark:text-slate-500 font-medium">Tambah konten menggunakan tombol Tambah Foto di atas.</p>
        </div>
        @endforelse
    </div>

    {{-- Modal Add/Edit --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
        <div class="relative bg-white dark:bg-admin-sidebar rounded-2xl w-full max-w-lg p-6 z-10 border border-gray-200 dark:border-white/10"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="font-bold mb-4 text-gray-900 dark:text-slate-100 font-jakarta text-sm uppercase tracking-wider" x-text="isEdit ? 'Edit Foto' : 'Tambah Foto'"></h3>
            <form x-show="!isEdit" method="POST" action="{{ route('admin.content.gallery.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @include('admin.partials.gallery-form')
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 text-xs font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition-all font-jakarta uppercase tracking-wider">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition-all font-jakarta uppercase tracking-wider">Simpan</button>
                </div>
            </form>
            <form x-show="isEdit" id="galleryEditForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                @include('admin.partials.gallery-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 text-xs font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition-all font-jakarta uppercase tracking-wider">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition-all font-jakarta uppercase tracking-wider">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDelete" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDelete=false"></div>
        <div class="relative bg-white dark:bg-admin-sidebar rounded-2xl p-6 z-10 w-full max-w-sm text-center border border-gray-200 dark:border-white/10">
            <div class="w-14 h-14 bg-red-950/40 border border-red-500/20 rounded-2xl flex items-center justify-center text-red-400 mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-slate-100 text-lg mb-2 font-jakarta">Hapus foto?</h3>
            <p class="text-gray-500 dark:text-slate-400 text-sm mb-6 font-medium">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button @click="showDelete=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 text-xs font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition-all font-jakarta uppercase tracking-wider">Batal</button>
                <form :action="`/admin/content/gallery/${deleteId}`" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all font-jakarta uppercase tracking-wider">Hapus</button>
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
