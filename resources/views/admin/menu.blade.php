@extends('admin.layouts.app')
@section('title','Menu & Produk')
@section('page-title','Menu & Produk')
@section('page-subtitle','Kelola semua item menu restoran')
@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
    class="mb-4 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 px-5 py-3.5 rounded-2xl shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span class="text-sm font-semibold">{{ session('success') }}</span>
    <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-700">✕</button>
</div>
@endif

<div x-data="{
    activeTab: 'grid',
    activeCategory: 'all',
    search: '',
    showModal: false,
    showDeleteModal: false,
    isEdit: false,
    deleteId: null,
    deleteName: '',
    canEdit: {{ auth()->user()->hasPermission('edit_menu') ? 'true' : 'false' }},
    canDelete: {{ auth()->user()->hasPermission('delete_data') ? 'true' : 'false' }},
    categories: ['all','makanan','minuman','dessert','snack','paket'],
    menus: {{ $menus->toJson() }},
    get filtered() {
        return this.menus.filter(m => {
            const cat = this.activeCategory === 'all' || m.category === this.activeCategory;
            const s = !this.search || m.name.toLowerCase().includes(this.search.toLowerCase());
            return cat && s;
        });
    },
    formatPrice(p) { return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(p); },
    openAdd() { this.isEdit = false; this.showModal = true; },
    openEdit(m) {
        this.isEdit = true;
        document.getElementById('edit_id').value = m.id;
        document.getElementById('edit_name').value = m.name;
        document.getElementById('edit_description').value = m.description || '';
        document.getElementById('edit_category').value = m.category;
        document.getElementById('edit_price').value = m.price;
        document.getElementById('edit_original_price').value = m.originalPrice || '';
        document.getElementById('edit_label').value = m.label || '';
        document.getElementById('edit_is_stock').checked = m.isStock;
        document.getElementById('edit_is_promo').checked = m.isPromo;
        document.getElementById('edit_is_new').checked = m.isNew;
        document.getElementById('edit_preview').src = m.image;
        document.getElementById('edit_preview_wrap').classList.remove('hidden');
        this.showModal = true;
    },
    confirmDelete(id, name) { this.deleteId = id; this.deleteName = name; this.showDeleteModal = true; }
}">

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1">
        <template x-for="cat in categories" :key="cat">
            <button @click="activeCategory=cat"
                :class="activeCategory===cat ? 'bg-violet-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-gray-55 dark:hover:bg-slate-700/50'"
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap border border-gray-200/50 dark:border-white/10 transition-all capitalize"
                x-text="cat==='all' ? 'Semua ('+menus.length+')' : cat">
            </button>
        </template>
    </div>

    <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto">
        <div class="relative flex-1 sm:flex-initial">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input x-model="search" type="text" placeholder="Cari menu..." class="pl-9 pr-4 py-2.5 text-xs bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl text-gray-700 dark:text-slate-300 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none w-full sm:w-48 font-semibold">
        </div>
        <div class="flex bg-gray-100 dark:bg-slate-700 rounded-2xl p-1">
            <button @click="activeTab='grid'" :class="activeTab==='grid'?'bg-white dark:bg-slate-600 shadow-sm text-violet-600 dark:text-white':''" class="p-2 rounded-xl text-slate-400 hover:text-gray-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </button>
            <button @click="activeTab='list'" :class="activeTab==='list'?'bg-white dark:bg-slate-600 shadow-sm text-violet-600 dark:text-white':''" class="p-2 rounded-xl text-slate-400 hover:text-gray-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </button>
        </div>
        <button @click="openAdd()" @if(!auth()->user()->hasPermission('edit_menu')) disabled title="Anda tidak memiliki izin" @endif
            class="flex items-center gap-2 {{ auth()->user()->hasPermission('edit_menu') ? 'bg-violet-600 hover:bg-violet-700' : 'bg-gray-300 dark:bg-slate-600 cursor-not-allowed' }} text-white text-xs font-bold px-4 py-2.5 rounded-2xl transition-all shadow-md shadow-violet-600/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Menu</span>
        </button>
    </div>
</div>

{{-- Grid View --}}
<div x-show="activeTab==='grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <template x-for="m in filtered" :key="m.id">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition-all group flex flex-col justify-between">
            <div>
                <div class="relative h-40 overflow-hidden">
                    <img :src="m.image" :alt="m.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                        <span x-show="m.label==='best-seller'" class="text-[9px] uppercase tracking-wider bg-orange-500 text-white px-2.5 py-1 rounded-full font-extrabold shadow-sm">Best Seller</span>
                        <span x-show="m.label==='new'" class="text-[9px] uppercase tracking-wider bg-emerald-500 text-white px-2.5 py-1 rounded-full font-extrabold shadow-sm">Baru</span>
                        <span x-show="m.label==='popular'" class="text-[9px] uppercase tracking-wider bg-blue-500 text-white px-2.5 py-1 rounded-full font-extrabold shadow-sm">Populer</span>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span :class="m.isStock ? 'bg-emerald-500/90 text-white' : 'bg-red-500/90 text-white'" class="text-[9px] uppercase tracking-wider px-2.5 py-1 rounded-full font-extrabold shadow-sm" x-text="m.isStock ? 'Tersedia' : 'Habis'"></span>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1 truncate" x-text="m.name"></h4>
                    <p class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-wider mb-3" x-text="m.category"></p>
                    <p class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 mb-4" x-text="m.description || 'Tidak ada deskripsi'"></p>
                </div>
            </div>

            <div class="p-4 pt-0">
                <div class="flex items-center justify-between mb-4 border-t border-gray-100 dark:border-slate-700/50 pt-3">
                    <span class="text-violet-600 dark:text-violet-400 font-extrabold text-sm" x-text="formatPrice(m.price)"></span>
                    <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500" x-text="`${m.reviews} Terjual`"></span>
                </div>
                <div class="flex gap-2">
                    <button @click="canEdit && openEdit(m)" :disabled="!canEdit"
                        :class="canEdit ? 'bg-violet-500/10 text-violet-600 hover:bg-violet-500/20 cursor-pointer' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed'"
                        class="flex-1 text-[10px] font-bold uppercase tracking-wider py-2 rounded-xl transition-all flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit</span>
                    </button>
                    <button @click="canDelete && confirmDelete(m.id, m.name)" :disabled="!canDelete"
                        :class="canDelete ? 'bg-red-500/10 text-red-600 hover:bg-red-500/20 cursor-pointer' : 'bg-gray-100 dark:bg-slate-750 text-gray-400 cursor-not-allowed'"
                        class="flex-1 text-[10px] font-bold uppercase tracking-wider py-2 rounded-xl transition-all flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <div x-show="filtered.length===0" class="col-span-full text-center py-20 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl">
        <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <p class="text-sm font-semibold text-gray-500 dark:text-slate-400">Tidak ada menu ditemukan</p>
    </div>
</div>

{{-- List View --}}
<div x-show="activeTab==='list'" class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="bg-gray-50/50 dark:bg-slate-700/30 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700/50">
                <th class="px-6 py-4">Menu</th>
                <th class="px-4 py-4">Kategori</th>
                <th class="px-4 py-4">Harga</th>
                <th class="px-4 py-4">Terjual</th>
                <th class="px-4 py-4">Status</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
            <template x-for="m in filtered" :key="m.id">
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <img :src="m.image" :alt="m.name" class="w-11 h-11 rounded-2xl object-cover flex-shrink-0 border border-gray-100 dark:border-slate-650">
                            <div>
                                <p class="font-bold text-gray-800 dark:text-slate-200 text-sm" x-text="m.name"></p>
                                <p class="text-xs text-gray-400 dark:text-slate-500 truncate max-w-[200px]" x-text="m.description || 'Tidak ada deskripsi'"></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider capitalize" x-text="m.category"></td>
                    <td class="px-4 py-3.5">
                        <div class="text-sm font-extrabold text-violet-600 dark:text-violet-400" x-text="formatPrice(m.price)"></div>
                        <div x-show="m.originalPrice" class="text-xs text-gray-400 dark:text-slate-500 line-through" x-text="m.originalPrice ? formatPrice(m.originalPrice) : ''"></div>
                    </td>
                    <td class="px-4 py-3.5 text-xs font-bold text-gray-500 dark:text-slate-400" x-text="m.reviews + ' porsi'"></td>
                    <td class="px-4 py-3.5">
                        <span :class="m.isStock ? 'bg-emerald-100/70 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-red-100/70 text-red-600 dark:bg-red-900/20 dark:text-red-400'" class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider" x-text="m.isStock ? 'Tersedia' : 'Habis'"></span>
                    </td>
                    <td class="px-6 py-3.5 text-right">
                        <div class="flex gap-1.5 justify-end">
                            <button @click="canEdit && openEdit(m)" :disabled="!canEdit"
                                :class="canEdit ? 'bg-violet-500/10 text-violet-600 hover:bg-violet-500/20 cursor-pointer' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed'"
                                class="w-8 h-8 rounded-xl flex items-center justify-center transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button @click="canDelete && confirmDelete(m.id, m.name)" :disabled="!canDelete"
                                :class="canDelete ? 'bg-red-500/10 text-red-600 hover:bg-red-500/20 cursor-pointer' : 'bg-gray-100 dark:bg-slate-750 text-gray-450 cursor-not-allowed'"
                                class="w-8 h-8 rounded-xl flex items-center justify-center transition-all" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg z-10 max-h-[90vh] flex flex-col"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex-shrink-0">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-lg" x-text="isEdit ? 'Edit Menu' : 'Tambah Menu Baru'"></h3>
                <p class="text-xs text-gray-400 mt-0.5" x-text="isEdit ? 'Perbarui informasi menu' : 'Isi detail menu baru'"></p>
            </div>
            <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-550 hover:bg-gray-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form Body --}}
        <div class="overflow-y-auto flex-1">
            {{-- ADD FORM --}}
            <form x-show="!isEdit" method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @include('admin.partials.menu-form', ['prefix' => 'add'])
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-2xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-violet-600/10 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Menu</span>
                    </button>
                </div>
            </form>

            {{-- EDIT FORM --}}
            <form x-show="isEdit" id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="_menu_id">
                @include('admin.partials.menu-form', ['prefix' => 'edit'])
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-2xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-violet-600/10 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Update Menu</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-sm z-10 p-6 text-center"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="w-14 h-14 bg-red-500/10 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>

        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Hapus Menu?</h3>
        <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">
            Menu "<strong x-text="deleteName" class="text-gray-900 dark:text-white"></strong>" akan dihapus secara permanen dari sistem.
        </p>
        <div class="flex gap-3">
            <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-2xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">Batal</button>
            <form :action="`/admin/menu/${deleteId}`" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>

</div>

{{-- Script: set edit form action dinamis --}}
<script>
document.addEventListener('alpine:init', () => {
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'edit_id') {
            document.getElementById('editForm').action = '/admin/menu/' + e.target.value;
        }
    });
});
const editIdEl = document.getElementById('edit_id');
if (editIdEl) {
    const observer = new MutationObserver(() => {
        const form = document.getElementById('editForm');
        if (form && editIdEl.value) form.action = '/admin/menu/' + editIdEl.value;
    });
    observer.observe(editIdEl, { attributes: true, attributeFilter: ['value'] });
}
</script>

@endsection
