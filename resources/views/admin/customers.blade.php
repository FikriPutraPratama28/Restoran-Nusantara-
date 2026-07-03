@extends('admin.layouts.app')
@section('title', 'Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Data pelanggan terdaftar')

@section('content')

@php
use App\Models\User;
$customers = User::where('role', 'pelanggan')
    ->orderBy('created_at', 'desc')
    ->paginate(20);
$total = User::where('role', 'pelanggan')->count();
$thisMonth = User::where('role', 'pelanggan')
    ->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->count();
$activeCount = User::where('role','pelanggan')->where('is_active',true)->count();
@endphp

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    {{-- Total Pelanggan --}}
    <div class="bg-white dark:bg-admin-card rounded-2xl p-5 relative overflow-hidden border border-gray-200 dark:border-white/[0.07]">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(139,92,246,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#a78bfa" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(139,92,246,0.1); color: #a78bfa;">
                Total Pelanggan
            </span>
        </div>
        <div class="font-extrabold text-gray-900 dark:text-slate-100 font-jakarta leading-tight" style="font-size: 24px; letter-spacing: -0.02em;">
            {{ number_format($total) }}
        </div>
        <div class="text-[12px] text-gray-500 dark:text-slate-500 mt-1">Akun Pelanggan</div>
        <div class="text-[11px] text-gray-400 dark:text-slate-600 mt-0.5 font-medium">Terdaftar di database</div>
    </div>

    {{-- Baru Bulan Ini --}}
    <div class="bg-white dark:bg-admin-card rounded-2xl p-5 relative overflow-hidden border border-gray-200 dark:border-white/[0.07]">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(52,211,153,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(52,211,153,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#34d399" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="16" y1="11" x2="22" y2="11"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(52,211,153,0.1); color: #34d399;">
                Baru Bulan Ini
            </span>
        </div>
        <div class="font-extrabold text-gray-900 dark:text-slate-100 font-jakarta leading-tight" style="font-size: 24px; letter-spacing: -0.02em;">
            +{{ number_format($thisMonth) }}
        </div>
        <div class="text-[12px] text-gray-500 dark:text-slate-500 mt-1">Registrasi Baru</div>
        <div class="text-[11px] text-gray-400 dark:text-slate-600 mt-0.5 font-medium">Bulan berjalan</div>
    </div>

    {{-- Akun Aktif --}}
    <div class="bg-white dark:bg-admin-card rounded-2xl p-5 relative overflow-hidden border border-gray-200 dark:border-white/[0.07]">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(56,189,248,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(56,189,248,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#38bdf8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(56,189,248,0.1); color: #38bdf8;">
                Akun Aktif
            </span>
        </div>
        <div class="font-extrabold text-gray-900 dark:text-slate-100 font-jakarta leading-tight" style="font-size: 24px; letter-spacing: -0.02em;">
            {{ number_format($activeCount) }}
        </div>
        <div class="text-[12px] text-gray-500 dark:text-slate-500 mt-1">Status Aktif</div>
        <div class="text-[11px] text-gray-400 dark:text-slate-600 mt-0.5 font-medium">Siap melakukan reservasi</div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-admin-card rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-white/[0.07]">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.06] flex items-center justify-between">
        <h3 class="font-bold text-gray-900 dark:text-slate-100 font-jakarta text-sm">Daftar Pelanggan</h3>
        <span class="text-xs text-gray-400 dark:text-slate-500">{{ $customers->total() }} total</span>
    </div>

    @if($customers->isEmpty())
    <div class="py-16 text-center">
        <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-200 dark:border-white/[0.06]">
            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="text-slate-400 text-sm">Belum ada pelanggan terdaftar</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-left border-b bg-gray-50/50 dark:bg-white/[0.02] border-gray-100 dark:border-white/[0.06]">
                    <th class="px-6 py-3.5">Pelanggan</th>
                    <th class="px-4 py-3.5">No. HP</th>
                    <th class="px-4 py-3.5">Bergabung</th>
                    <th class="px-4 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-white/[0.06]">
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                                class="w-9 h-9 rounded-xl object-cover flex-shrink-0 border border-gray-200 dark:border-white/[0.1]">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">
                        {{ $customer->phone ?? '—' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400 font-medium">
                        {{ $customer->created_at->locale('id')->isoFormat('D MMM YYYY') }}
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $customer->is_active ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20' : 'bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20' }}">
                            {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/[0.06]">
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection
