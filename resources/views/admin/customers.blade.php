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
@endphp

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center text-lg shadow mb-3">👥</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $total }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">Total Pelanggan</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-lg shadow mb-3">🆕</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $thisMonth }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">Baru Bulan Ini</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center text-lg shadow mb-3">✅</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ User::where('role','pelanggan')->where('is_active',true)->count() }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">Akun Aktif</div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 dark:text-white">Daftar Pelanggan</h3>
        <span class="text-xs text-gray-400 dark:text-slate-500">{{ $customers->total() }} total</span>
    </div>

    @if($customers->isEmpty())
    <div class="py-16 text-center">
        <div class="text-5xl mb-4">👥</div>
        <p class="text-gray-400 dark:text-slate-500 text-sm">Belum ada pelanggan terdaftar</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3.5 uppercase tracking-wider">Pelanggan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">No. HP</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Bergabung</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                                class="w-9 h-9 rounded-xl object-cover flex-shrink-0 border border-gray-100 dark:border-slate-600">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400">
                        {{ $customer->phone ?? '—' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400">
                        {{ $customer->created_at->locale('id')->isoFormat('D MMM YYYY') }}
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $customer->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection
