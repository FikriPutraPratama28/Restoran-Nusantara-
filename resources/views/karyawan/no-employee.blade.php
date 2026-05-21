@extends('karyawan.layouts.app')
@section('title', 'Akun Belum Terdaftar')
@section('page-title', 'Akun Belum Lengkap')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
    <div class="text-7xl mb-6">⚠️</div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Data Karyawan Belum Terdaftar</h2>
    <p class="text-gray-500 dark:text-slate-400 text-sm max-w-md mb-6">
        Akun Anda sudah aktif sebagai karyawan, namun data profil karyawan belum dibuat oleh admin.
        Hubungi administrator untuk melengkapi data Anda.
    </p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all">
            Logout
        </button>
    </form>
</div>
@endsection
