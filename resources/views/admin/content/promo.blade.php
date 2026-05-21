@extends('admin.layouts.app')
@section('title','Promo & Voucher')
@section('page-title','Promo & Voucher')
@section('page-subtitle','Kelola kode promo dan diskon')
@section('content')

@include('admin.partials.flash')

<div x-data="{showModal:false,showDeleteModal:false,isEdit:false,deleteId:null,editPromo:null,
    openAdd(){ this.isEdit=false; this.editPromo=null; this.showModal=true; },
    openEdit(p){ this.isEdit=true; this.editPromo=p; this.showModal=true; },
    confirmDelete(id){ this.deleteId=id; this.showDeleteModal=true; }
}">

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $promos->count() }} promo aktif</p>
    <button @click="openAdd()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-all shadow-lg shadow-violet-600/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Promo
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($promos as $promo)
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $promo->gradient }} p-5 text-white group">
        <div class="absolute -right-6 -top-6 w-28 h-28 bg-white/10 rounded-full"></div>
        <div class="absolute -right-3 bottom-3 w-20 h-20 bg-white/10 rounded-full"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-3">
                <span class="text-3xl">{{ $promo->icon }}</span>
                <div class="flex gap-1">
                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full font-semibold">{{ $promo->badge }}</span>
                    <span class="text-xs {{ $promo->is_active ? 'bg-emerald-500' : 'bg-gray-500' }} px-2 py-0.5 rounded-full font-semibold">
                        {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <h3 class="font-bold mb-1">{{ $promo->title }}</h3>
            <p class="text-white/70 text-xs mb-3">{{ $promo->description }}</p>
            <div class="flex items-center gap-2 mb-3">
                <div class="bg-white/20 rounded-lg px-3 py-1.5 flex-1">
                    <span class="font-mono font-bold text-sm">{{ $promo->code }}</span>
                </div>
                <span class="text-xs bg-white/20 px-2 py-1.5 rounded-lg font-semibold">{{ $promo->discount_label }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white/50 text-xs">{{ $promo->expiry_label }}</span>
                <div class="flex gap-1">
                    <button @click="openEdit({{ $promo->toJson() }})"
                        class="w-7 h-7 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button @click="confirmDelete({{ $promo->id }})"
                        class="w-7 h-7 bg-red-500/30 hover:bg-red-500/50 rounded-lg flex items-center justify-center transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700">
        <div class="text-5xl mb-3">🎁</div>
        <p class="text-gray-500 dark:text-slate-400">Belum ada promo. Tambahkan promo pertama!</p>
    </div>
    @endforelse
</div>

{{-- ADD/EDIT MODAL --}}
<div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg z-10 max-h-[90vh] flex flex-col"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex-shrink-0">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg" x-text="isEdit ? 'Edit Promo' : 'Tambah Promo Baru'"></h3>
            <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="overflow-y-auto flex-1">
            <form x-show="!isEdit" method="POST" action="{{ route('admin.content.promo.store') }}" class="p-6 space-y-4">
                @csrf
                @include('admin.partials.promo-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all">Simpan</button>
                </div>
            </form>
            <form x-show="isEdit" id="promoEditForm" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                @include('admin.partials.promo-form')
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
        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Hapus Promo?</h3>
        <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">Promo ini akan dihapus permanen.</p>
        <div class="flex gap-3">
            <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
            <form :action="`/admin/content/promo/${deleteId}`" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>

</div>

<script>
document.addEventListener('click', function() {
    const form = document.getElementById('promoEditForm');
    const el = document.querySelector('[x-data*="editPromo"]');
    if (!form || !el) return;
    const promo = Alpine.evaluate(el, 'editPromo');
    if (promo) form.action = `/admin/content/promo/${promo.id}`;
});
</script>
@endsection
