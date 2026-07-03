@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-dark-900 pt-24 pb-12">
    <div class="max-w-2xl mx-auto px-4">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1">Kelola informasi akun Anda</p>
        </div>

        {{-- Flash --}}
        @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 mb-5 flex items-center gap-3">
            <span class="text-emerald-500 text-xl">✅</span>
            <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 overflow-hidden">

            {{-- Cover / Avatar --}}
            <div class="h-24 bg-gradient-to-br from-violet-500 to-blue-600 relative">
                <div class="absolute -bottom-10 left-6">
                    <div class="relative" x-data="{ preview: '{{ $user->avatar_url }}' }">
                        <img :src="preview" alt="{{ $user->name }}"
                            class="w-20 h-20 rounded-2xl object-cover border-4 border-white dark:border-dark-800 shadow-lg">
                        <label class="absolute -bottom-1 -right-1 w-7 h-7 bg-violet-600 hover:bg-violet-700 rounded-xl flex items-center justify-center cursor-pointer shadow-lg transition-all">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <input type="file" class="hidden" id="avatarInput" accept="image/*"
                                onchange="document.querySelector('[\\:src]').src = URL.createObjectURL(this.files[0])">
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-14 px-6 pb-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-gray-500 dark:text-slate-400 text-sm">{{ $user->email }}</p>
                        <span class="inline-block mt-1 text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700' : ($user->role === 'karyawan' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    @if($user->isKaryawan() && $user->employee)
                    <div class="text-right">
                        <p class="text-xs text-gray-400 dark:text-slate-500">Kode Karyawan</p>
                        <p class="font-mono font-bold text-violet-600 dark:text-violet-400">{{ $user->employee->employee_code }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $user->employee->jabatan }}</p>
                    </div>
                    @endif
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" x-data="{}">
                    @csrf
                    {{-- Hidden avatar input yang terhubung ke preview --}}
                    <input type="file" name="avatar" id="avatarFormInput" accept="image/*" class="hidden"
                        onchange="document.getElementById('avatarInput').files = this.files; document.querySelector('img[alt=\'{{ $user->name }}\']').src = URL.createObjectURL(this.files[0])">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200 dark:border-dark-600' }} bg-gray-50 dark:bg-dark-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-100 dark:bg-dark-600 text-sm text-gray-400 dark:text-slate-500 cursor-not-allowed">
                            <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">No. HP</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-dark-600 bg-gray-50 dark:bg-dark-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Foto Profil</label>
                            <input type="file" name="avatar" accept="image/*"
                                class="block text-sm text-gray-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 dark:file:bg-violet-900/30 dark:file:text-violet-400 hover:file:bg-violet-100 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP. Maks 2MB</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6 pt-5 border-t border-gray-100 dark:border-dark-700">
                        <a href="{{ url()->previous() }}"
                            class="px-5 py-2.5 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all">
                            Kembali
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-violet-600/30 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Quick Links --}}
        @if($user->isKaryawan())
        <div class="mt-4 grid grid-cols-3 gap-3">
            @foreach([
                ['href'=>route('karyawan.dashboard'),  'icon'=>'🏠', 'label'=>'Dashboard'],
                ['href'=>route('karyawan.attendance'), 'icon'=>'📍', 'label'=>'Absensi'],
                ['href'=>route('karyawan.leave'),      'icon'=>'🏖️', 'label'=>'Cuti'],
            ] as $link)
            <a href="{{ $link['href'] }}" class="bg-white dark:bg-dark-800 rounded-2xl p-4 text-center border border-gray-100 dark:border-dark-700 hover:border-violet-200 dark:hover:border-violet-800 hover:shadow-md transition-all">
                <div class="text-2xl mb-1">{{ $link['icon'] }}</div>
                <div class="text-xs font-semibold text-gray-600 dark:text-slate-400">{{ $link['label'] }}</div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
