@extends('admin.layouts.app')
@section('title','Manajemen User')
@section('page-title','Manajemen User')
@section('page-subtitle','Kelola semua akun pengguna sistem')
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
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
    class="mb-4 flex items-center gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 px-5 py-3.5 rounded-2xl shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span class="text-sm font-semibold">{{ session('error') }}</span>
    <button @click="show=false" class="ml-auto text-red-500 hover:text-red-700">✕</button>
</div>
@endif
@if($errors->any())
<div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 px-5 py-3.5 rounded-2xl shadow-sm">
    <p class="text-sm font-semibold mb-1">⚠️ Ada kesalahan:</p>
    @foreach($errors->all() as $e)
    <p class="text-sm">• {{ $e }}</p>
    @endforeach
</div>
@endif

<div x-data="{
    showModal: false,
    showDeleteModal: false,
    isEdit: false,
    editUser: null,
    deleteUser: null,
    ajaxMsg: null,
    ajaxMsgType: 'success',

    openAdd() {
        this.isEdit = false;
        this.editUser = null;
        this.showModal = true;
        this.$nextTick(() => {
            document.getElementById('addForm').reset();
        });
    },

    openEdit(user) {
        this.isEdit = true;
        this.editUser = user;
        this.showModal = true;
        this.$nextTick(() => {
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_role').value = user.role;
            document.getElementById('edit_is_active').checked = user.is_active;
            document.getElementById('editForm').action = '/admin/users/' + user.id;
        });
    },

    confirmDelete(user) {
        this.deleteUser = user;
        this.showDeleteModal = true;
    },

    showAlert(msg, type) {
        this.ajaxMsg = msg;
        this.ajaxMsgType = type;
        setTimeout(() => { this.ajaxMsg = null; }, 4000);
    },

    async toggleActive(userId, userName) {
        try {
            const response = await fetch('/admin/users/' + userId + '/toggle-active', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            const data = await response.json();
            if (data.success) {
                this.showAlert(data.message, 'success');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                this.showAlert(data.message || 'Gagal mengubah status.', 'error');
            }
        } catch (e) {
            this.showAlert('Terjadi kesalahan jaringan.', 'error');
        }
    }
}">

{{-- AJAX Alert --}}
<div x-show="ajaxMsg" x-cloak
    :class="ajaxMsgType === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-700 dark:text-emerald-400' : 'bg-red-50 border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-700 dark:text-red-400'"
    class="mb-4 flex items-center gap-3 border px-5 py-3.5 rounded-2xl shadow-sm">
    <span class="text-sm font-semibold" x-text="ajaxMsg"></span>
    <button @click="ajaxMsg=null" class="ml-auto opacity-70 hover:opacity-100">✕</button>
</div>

{{-- Header: jumlah user + tombol tambah --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white flex-shrink-0"
             style="background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 4px 12px rgba(124,58,237,0.25);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white text-base">Semua User</h2>
            <p class="text-xs text-slate-500">Total {{ $users->count() }} pengguna terdaftar</p>
        </div>
    </div>
    <button @click="openAdd()"
        class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-4 py-2.5 rounded-2xl transition-all shadow-md shadow-violet-600/10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah User</span>
    </button>
</div>

{{-- Filter & Search --}}
<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row gap-3 w-full">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                class="w-full pl-9 pr-4 py-2.5 text-xs bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl text-gray-700 dark:text-slate-300 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none font-semibold">
        </div>
        <select name="role"
            class="px-4 py-2.5 text-xs font-semibold bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="all" {{ request('role','all')==='all' ? 'selected' : '' }}>Semua Role</option>
            <option value="super_admin" {{ request('role')==='super_admin' ? 'selected' : '' }}>Super Admin</option>
            <option value="admin" {{ request('role')==='admin' ? 'selected' : '' }}>Admin</option>

        </select>
        <button type="submit"
            class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-2xl transition-all shadow-sm">
            Filter
        </button>
        @if(request('search') || (request('role') && request('role') !== 'all'))
        <a href="{{ route('admin.users') }}"
            class="px-5 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-600 dark:text-slate-300 text-xs font-bold rounded-2xl transition-all text-center">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Tabel User --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    @if($users->isEmpty())
    <div class="text-center py-20">
        <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/>
        </svg>
        <p class="text-sm font-semibold text-gray-500 dark:text-slate-400">Tidak ada user ditemukan</p>
    </div>
    @else
    <div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="bg-gray-50/50 dark:bg-slate-700/30 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700/50">
                <th class="px-6 py-4">User</th>
                <th class="px-4 py-4">Role</th>
                <th class="px-4 py-4">Status</th>
                <th class="px-4 py-4">Tgl. Daftar</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
            @foreach($users as $u)
            @php
                $isSelf = $u->id === auth()->id();
                $initials = strtoupper(substr($u->name, 0, 1));
                $avatarGradient = match($u->role) {
                    'super_admin' => 'from-purple-500 to-purple-700',
                    'admin'       => 'from-blue-500 to-blue-700',
                    default       => 'from-gray-400 to-gray-600',
                };
            @endphp
            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors">
                {{-- Avatar + Nama + Email --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0 bg-gradient-to-br {{ $avatarGradient }}">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-slate-200 text-sm">
                                {{ $u->name }}
                                @if($isSelf)
                                <span class="ml-1 text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 uppercase tracking-wide">Anda</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 dark:text-slate-500">{{ $u->email }}</p>
                        </div>
                    </div>
                </td>
                {{-- Role Badge --}}
                <td class="px-4 py-4">
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $u->role_badge_color }}">
                        {{ $u->role_label }}
                    </span>
                </td>
                {{-- Status --}}
                <td class="px-4 py-4">
                    <span class="{{ $u->is_active ? 'bg-emerald-100/70 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-red-100/70 text-red-600 dark:bg-red-900/20 dark:text-red-400' }} text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                {{-- Tgl Daftar --}}
                <td class="px-4 py-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                    {{ $u->created_at->format('d M Y') }}
                </td>
                {{-- Aksi --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5 justify-end">
                        {{-- Edit --}}
                        <button
                            @click="openEdit({{ json_encode(['id'=>$u->id,'name'=>$u->name,'email'=>$u->email,'role'=>$u->role,'is_active'=>$u->is_active]) }})"
                            class="w-8 h-8 rounded-xl flex items-center justify-center bg-violet-500/10 text-violet-600 hover:bg-violet-500/20 transition-all" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        {{-- Toggle Aktif --}}
                        <button
                            @click="toggleActive({{ $u->id }}, '{{ addslashes($u->name) }}')"
                            class="w-8 h-8 rounded-xl flex items-center justify-center transition-all {{ $u->is_active ? 'bg-amber-500/10 text-amber-600 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500/20' }}"
                            title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            @if($u->is_active)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </button>
                        {{-- Hapus: sembunyikan jika user adalah diri sendiri --}}
                        @if(!$isSelf)
                        <button
                            @click="confirmDelete({{ json_encode(['id'=>$u->id,'name'=>$u->name]) }})"
                            class="w-8 h-8 rounded-xl flex items-center justify-center bg-red-500/10 text-red-600 hover:bg-red-500/20 transition-all" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @else
                        <div class="w-8 h-8"></div>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @endif
</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div x-show="showModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md z-10"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-lg" x-text="isEdit ? 'Edit User' : 'Tambah User Baru'"></h3>
                <p class="text-xs text-gray-400 mt-0.5" x-text="isEdit ? 'Perbarui informasi akun' : 'Isi detail user baru'"></p>
            </div>
            <button @click="showModal=false" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- ADD FORM --}}
        <form x-show="!isEdit" id="addForm" method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Masukkan nama lengkap"
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" required placeholder="email@contoh.com"
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" required
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                    <option value="">-- Pilih Role --</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>

                </select>
            </div>
            <div class="flex items-center justify-between py-2">
                <div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-slate-300">Status Aktif</p>
                    <p class="text-xs text-gray-400">User dapat login jika aktif</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                    <div class="w-10 h-6 bg-gray-200 dark:bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-violet-600/10 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan User
                </button>
            </div>
        </form>

        {{-- EDIT FORM --}}
        <form x-show="isEdit" id="editForm" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="edit_name" name="name" required placeholder="Masukkan nama lengkap"
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" id="edit_email" name="email" required placeholder="email@contoh.com"
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Password <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                <input type="password" id="edit_password" name="password" placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select id="edit_role" name="role" required
                    class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>

                </select>
            </div>
            <div class="flex items-center justify-between py-2">
                <div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-slate-300">Status Aktif</p>
                    <p class="text-xs text-gray-400">User dapat login jika aktif</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="sr-only peer">
                    <div class="w-10 h-6 bg-gray-200 dark:bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="showModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-violet-600/10 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDeleteModal=false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6 text-center"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

        <div class="w-14 h-14 bg-red-500/10 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>

        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Hapus User?</h3>
        <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">
            User "<strong x-text="deleteUser ? deleteUser.name : ''" class="text-gray-900 dark:text-white"></strong>" akan dihapus secara permanen dari sistem.
        </p>
        <div class="flex gap-3">
            <button @click="showDeleteModal=false" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">Batal</button>
            <form id="deleteForm" method="POST" class="flex-1" :action="deleteUser ? '/admin/users/' + deleteUser.id : '#'">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>

</div>{{-- end x-data --}}

@endsection
