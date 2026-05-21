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
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">👥 Tim Kami</h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $team->count() }} anggota tim</p>
            </div>
            <button @click="openAdd()" class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-medium px-3 py-2 rounded-xl transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah
            </button>
        </div>
        <div class="p-4 grid grid-cols-2 gap-3">
            @forelse($team as $member)
            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-xl p-4 text-center group relative">
                @if($member->image_src)
                    <img src="{{ $member->image_src }}" alt="{{ $member->name }}" class="w-16 h-16 rounded-2xl object-cover mx-auto mb-2 shadow-lg">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br {{ $member->gradient }} rounded-2xl flex items-center justify-center text-3xl mx-auto mb-2 shadow-lg">{{ $member->emoji }}</div>
                @endif
                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $member->name }}</p>
                <p class="text-gray-500 dark:text-slate-400 text-xs">{{ $member->role }}</p>
                <div class="flex gap-1 justify-center mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="openEdit({{ $member->toJson() }})" class="w-7 h-7 bg-violet-100 dark:bg-violet-900/30 text-violet-600 rounded-lg flex items-center justify-center hover:bg-violet-200 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button @click="confirmDelete({{ $member->id }})" class="w-7 h-7 bg-red-100 dark:bg-red-900/30 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-200 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-8">
                <div class="text-4xl mb-2">👥</div>
                <p class="text-gray-500 dark:text-slate-400 text-sm">Belum ada anggota tim</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Team Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md z-10 max-h-[90vh] flex flex-col"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex-shrink-0">
                <h3 class="font-bold text-gray-900 dark:text-white" x-text="isEdit ? 'Edit Anggota Tim' : 'Tambah Anggota Tim'"></h3>
                <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1">
                <form x-show="!isEdit" method="POST" action="{{ route('admin.content.team.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    @include('admin.partials.team-form')
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all">Simpan</button>
                    </div>
                </form>
                <form x-show="isEdit" id="teamEditForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf @method('PUT')
                    @include('admin.partials.team-form')
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal=false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6 text-center">
            <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">🗑️</div>
            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Hapus Anggota Tim?</h3>
            <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">Data anggota tim akan dihapus permanen.</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-medium hover:bg-gray-50 transition-all">Batal</button>
                <form :action="`/admin/content/team/${deleteId}`" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-all">Hapus</button>
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
