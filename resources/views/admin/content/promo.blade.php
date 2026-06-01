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
    <p class="text-sm text-slate-500 font-medium font-jakarta">{{ $promos->count() }} promo aktif</p>
    <button @click="openAdd()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-violet-600/20 uppercase tracking-wider font-jakarta">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Promo
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($promos as $promo)
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $promo->gradient }} p-5 text-white group shadow-lg">
        <div class="absolute -right-6 -top-6 w-28 h-28 bg-white/10 rounded-full"></div>
        <div class="absolute -right-3 bottom-3 w-20 h-20 bg-white/10 rounded-full"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-3">
                <span class="text-3xl">
                    {{-- Render nice action-based icon instead of raw emoji if database stores string --}}
                    @if($promo->icon === '🍔' || $promo->icon === 'food')
                        <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    @elseif($promo->icon === '🥤' || $promo->icon === 'drink')
                        <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    @elseif($promo->icon === '🎂' || $promo->icon === 'dessert')
                        <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>
                    @elseif($promo->icon === '👤' || $promo->icon === 'user')
                        <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @elseif($promo->icon === '🎉' || $promo->icon === 'cashback')
                        <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364.364l-.707.707M21 12h-1m-.364 6.364l-.707-.707M12 21v-1m-7.657-.364l.707-.707M3 12h1m.364-6.364l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    @else
                        <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    @endif
                </span>
                <div class="flex gap-1">
                    <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider font-jakarta">{{ $promo->badge }}</span>
                    <span class="text-[10px] {{ $promo->is_active ? 'bg-emerald-500' : 'bg-gray-500' }} px-2 py-0.5 rounded-full font-bold uppercase tracking-wider font-jakarta">
                        {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <h3 class="font-bold mb-1 font-jakarta text-[15px]">{{ $promo->title }}</h3>
            <p class="text-white/70 text-xs mb-3 font-medium">{{ $promo->description }}</p>
            <div class="flex items-center gap-2 mb-3">
                <div class="bg-white/20 rounded-lg px-3 py-1.5 flex-1">
                    <span class="font-mono font-bold text-sm tracking-wider">{{ $promo->code }}</span>
                </div>
                <span class="text-xs bg-white/20 px-2 py-1.5 rounded-lg font-bold font-jakarta">{{ $promo->discount_label }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white/50 text-[10px] font-bold uppercase tracking-wider font-jakarta">{{ $promo->expiry_label }}</span>
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
    <div class="col-span-3 text-center py-16 bg-admin-card rounded-2xl" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="w-16 h-16 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border" style="border-color: rgba(255,255,255,0.06);">
            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-medium">Belum ada promo. Tambahkan promo pertama!</p>
    </div>
    @endforelse
</div>

{{-- ADD/EDIT MODAL --}}
<div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
    <div class="relative bg-admin-sidebar rounded-2xl w-full max-w-lg z-10 max-h-[90vh] flex flex-col border border-white/10"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0" style="border-bottom-color: rgba(255,255,255,0.06);">
            <h3 class="font-bold text-slate-100 text-sm font-jakarta uppercase tracking-wider" x-text="isEdit ? 'Edit Promo' : 'Tambah Promo Baru'"></h3>
            <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white transition-all border border-slate-700/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="overflow-y-auto flex-1">
            <form x-show="!isEdit" method="POST" action="{{ route('admin.content.promo.store') }}" class="p-6 space-y-4">
                @csrf
                @include('admin.partials.promo-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-400 text-xs font-bold hover:bg-slate-800 uppercase tracking-wider transition-all font-jakarta">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold uppercase tracking-wider transition-all font-jakarta">Simpan</button>
                </div>
            </form>
            <form x-show="isEdit" id="promoEditForm" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                @include('admin.partials.promo-form')
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-400 text-xs font-bold hover:bg-slate-800 uppercase tracking-wider transition-all font-jakarta">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold uppercase tracking-wider transition-all font-jakarta">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal=false"></div>
    <div class="relative bg-admin-sidebar rounded-2xl w-full max-w-sm z-10 p-6 text-center border border-white/10 shadow-2xl">
        <div class="w-14 h-14 bg-red-950/40 border border-red-500/20 rounded-2xl flex items-center justify-center text-red-400 mx-auto mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="font-bold text-slate-100 text-lg mb-2 font-jakarta">Hapus Promo?</h3>
        <p class="text-slate-400 text-sm mb-6 font-medium">Promo ini akan dihapus permanen.</p>
        <div class="flex gap-3">
            <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-400 text-xs font-bold hover:bg-slate-800 transition-all font-jakarta uppercase tracking-wider">Batal</button>
            <form :action="`/admin/content/promo/${deleteId}`" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all font-jakarta uppercase tracking-wider">Hapus</button>
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
