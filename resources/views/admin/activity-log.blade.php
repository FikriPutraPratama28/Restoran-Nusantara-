@extends('admin.layouts.app')
@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('page-subtitle', 'Rekam jejak semua aktivitas sistem')

@section('content')

{{-- KPI --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    @php
    $moduleColors = [
        'Menu'       => 'from-orange-500 to-amber-600',
        'Attendance' => 'from-emerald-500 to-teal-600',
        'Leave'      => 'from-blue-500 to-cyan-600',
        'Employee'   => 'from-violet-500 to-purple-600',
        'Auth'       => 'from-pink-500 to-rose-600',
        'Content'    => 'from-indigo-500 to-blue-600',
        'System'     => 'from-gray-500 to-slate-600',
    ];
    @endphp
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-11 h-11 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center text-xl shadow-lg mb-3">📋</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">Total Log</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-xl shadow-lg mb-3">📅</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['today']) }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">Aktivitas Hari Ini</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center text-xl shadow-lg mb-3">🗂️</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($stats['modules']) }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">Modul Aktif</div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-11 h-11 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center text-xl shadow-lg mb-3">👥</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $logs->total() }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">Hasil Filter</div>
    </div>
</div>

{{-- Distribusi per Modul --}}
@if(!empty($stats['modules']))
<div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 mb-5">
    <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-sm">Distribusi per Modul</h3>
    <div class="flex flex-wrap gap-2">
        @foreach($stats['modules'] as $mod => $cnt)
        @php $color = $moduleColors[$mod] ?? 'from-gray-500 to-slate-600'; @endphp
        <a href="{{ request()->fullUrlWithQuery(['module' => $mod, 'page' => 1]) }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-gradient-to-r {{ $color }} text-white shadow hover:opacity-90 transition-all">
            {{ $mod }} <span class="bg-white/20 px-1.5 py-0.5 rounded-lg">{{ $cnt }}</span>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- Filter --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 mb-5">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari aktivitas atau nama..."
            class="flex-1 min-w-[180px] px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
        <select name="module" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="">Semua Modul</option>
            @foreach($modules as $m)
            <option value="{{ $m }}" {{ $module === $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
        <select name="role" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="">Semua Role</option>
            @foreach($roles as $r)
            <option value="{{ $r }}" {{ $role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ $date }}"
            class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">Filter</button>
        @if($search || $module || $role || $date)
        <a href="{{ route('admin.activity-log') }}" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all">Reset</a>
        @endif
    </form>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 mb-4 flex items-center gap-3">
    <span class="text-emerald-500 text-xl">✅</span>
    <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
</div>
@endif

{{-- Log Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
        <div>
            <h3 class="font-bold text-gray-900 dark:text-white">Log Aktivitas</h3>
            <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ $logs->total() }} entri ditemukan</p>
        </div>
        {{-- Clear old logs --}}
        @if(auth()->user()->hasPermission('clear_activity_log'))
        <form method="POST" action="{{ route('admin.activity-log.clear') }}"
            onsubmit="return confirm('Hapus log lebih dari 30 hari yang lalu?')">
            @csrf @method('DELETE')
            <input type="hidden" name="days" value="30">
            <button type="submit"
                class="flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Bersihkan Log Lama
            </button>
        </form>
        @endif
    </div>

    @if($logs->isEmpty())
    <div class="py-16 text-center">
        <div class="text-5xl mb-4">📋</div>
        <h3 class="text-lg font-bold text-gray-700 dark:text-slate-300 mb-2">Tidak ada log</h3>
        <p class="text-gray-400 dark:text-slate-500 text-sm">Belum ada aktivitas yang tercatat atau filter tidak menemukan hasil</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-slate-700/50">
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Waktu</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Aktor</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Modul</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Aktivitas</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider hidden lg:table-cell">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($logs as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    {{-- Waktu --}}
                    <td class="px-4 py-3.5 whitespace-nowrap">
                        <div class="text-xs font-semibold text-gray-700 dark:text-slate-300">{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-400 dark:text-slate-500 font-mono">{{ $log->created_at->format('H:i:s') }}</div>
                        <div class="text-[10px] text-gray-300 dark:text-slate-600 mt-0.5">{{ $log->created_at->diffForHumans() }}</div>
                    </td>
                    {{-- Aktor --}}
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            @if($log->user)
                            <img src="{{ $log->user->avatar_url }}" class="w-7 h-7 rounded-lg object-cover flex-shrink-0">
                            @else
                            <div class="w-7 h-7 bg-gray-200 dark:bg-slate-600 rounded-lg flex items-center justify-center text-xs flex-shrink-0">⚙️</div>
                            @endif
                            <div>
                                <div class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $log->actor ?? 'System' }}</div>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full capitalize
                                    {{ $log->role === 'admin' ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400' :
                                       ($log->role === 'karyawan' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                       ($log->role === 'pelanggan' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' :
                                       'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-400')) }}">
                                    {{ $log->role ?? 'system' }}
                                </span>
                            </div>
                        </div>
                    </td>
                    {{-- Modul --}}
                    <td class="px-4 py-3.5">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $log->color }}">
                            {{ $log->icon }} {{ $log->module }}
                        </span>
                    </td>
                    {{-- Deskripsi --}}
                    <td class="px-4 py-3.5 max-w-xs">
                        <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed">{{ $log->description }}</p>
                        @if($log->properties)
                        <details class="mt-1">
                            <summary class="text-xs text-violet-500 cursor-pointer hover:text-violet-700">Detail data</summary>
                            <pre class="text-[10px] text-gray-500 dark:text-slate-400 mt-1 bg-gray-50 dark:bg-slate-700 p-2 rounded-lg overflow-x-auto">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </details>
                        @endif
                    </td>
                    {{-- IP --}}
                    <td class="px-4 py-3.5 hidden lg:table-cell">
                        <span class="text-xs font-mono text-gray-400 dark:text-slate-500">{{ $log->ip_address ?? '—' }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection
