@extends('admin.layouts.app')
@section('title','Tim Kami')
@section('page-title','Tim Kami')
@section('page-subtitle','Kelola anggota tim')
@section('content')

@include('admin.partials.flash')

<div x-data="{showModal:false,showDeleteModal:false,isEdit:false,deleteId:null,editMember:null,
    openAdd(){ this.isEdit=false; this.editMember=null; this.showModal=true; },
    openEdit(m){ this.isEdit=true; this.editMember=m; this.showModal=true; },
    confirmDelete(id){ this.deleteId=id; this.showDeleteModal=true; }
}">
    <div class="bg-admin-card rounded-2xl overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-bottom-color: rgba(255,255,255,0.06);">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-violet-950/30 rounded-lg flex items-center justify-center border border-violet-500/10 text-violet-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-100 font-jakarta text-sm uppercase tracking-wider">Tim Kami</h3>
            </div>
            <button @click="openAdd()" class="flex items-center gap-1.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-violet-600/20 uppercase tracking-wider font-jakarta">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Anggota
            </button>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($team as $member)
            <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl p-5 text-center group relative overflow-hidden transition-all hover:bg-slate-950/60">
                @if($member->image_src)
                    <img src="{{ $member->image_src }}" alt="{{ $member->name }}" class="w-16 h-16 rounded-2xl object-cover mx-auto mb-3 shadow-lg border border-slate-800">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br {{ $member->gradient }} rounded-2xl flex items-center justify-center text-white text-xl font-bold mx-auto mb-3 shadow-lg">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif
                <p class="font-bold text-slate-200 text-[14px] font-jakarta">{{ $member->name }}</p>
                <p class="text-slate-500 text-xs mt-0.5 font-medium">{{ $member->role }}</p>
                <div class="flex gap-1.5 justify-center mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="openEdit({{ $member->toJson() }})" class="w-7 h-7 bg-slate-800 border border-slate-700 text-slate-300 rounded-lg flex items-center justify-center hover:bg-slate-700 transition-all" title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button @click="confirmDelete({{ $member->id }})" class="w-7 h-7 bg-red-950/30 border border-red-500/10 text-red-400 rounded-lg flex items-center justify-center hover:bg-red-900/20 transition-all" title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="w-16 h-16 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border" style="border-color: rgba(255,255,255,0.06);">
                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-slate-400 text-sm font-medium">Belum ada anggota tim</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Team Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
        <div class="relative bg-admin-sidebar rounded-2xl w-full max-w-md z-10 max-h-[90vh] flex flex-col border border-white/10"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0" style="border-bottom-color: rgba(255,255,255,0.06);">
                <h3 class="font-bold text-slate-100 font-jakarta text-sm uppercase tracking-wider" x-text="isEdit ? 'Edit Anggota Tim' : 'Tambah Anggota Tim'"></h3>
                <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white transition-all border border-slate-700/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1">
                <form x-show="!isEdit" method="POST" action="{{ route('admin.content.team.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    @include('admin.partials.team-form')
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-400 text-xs font-bold hover:bg-slate-800 transition-all font-jakarta uppercase tracking-wider">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition-all font-jakarta uppercase tracking-wider">Simpan</button>
                    </div>
                </form>
                <form x-show="isEdit" id="teamEditForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf @method('PUT')
                    @include('admin.partials.team-form')
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-400 text-xs font-bold hover:bg-slate-800 transition-all font-jakarta uppercase tracking-wider">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition-all font-jakarta uppercase tracking-wider">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal=false"></div>
        <div class="relative bg-admin-sidebar rounded-2xl w-full max-w-sm z-10 p-6 text-center border border-white/10 shadow-2xl">
            <div class="w-14 h-14 bg-red-950/40 border border-red-500/20 rounded-2xl flex items-center justify-center text-red-400 mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="font-bold text-slate-100 text-lg mb-2 font-jakarta">Hapus Anggota Tim?</h3>
            <p class="text-slate-400 text-sm mb-6 font-medium">Data anggota tim akan dihapus permanen.</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-400 text-xs font-bold hover:bg-slate-800 transition-all font-jakarta uppercase tracking-wider">Batal</button>
                <form :action="`/admin/content/team/${deleteId}`" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all font-jakarta uppercase tracking-wider">Hapus</button>
                </form>
            </div>
        </div>
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

