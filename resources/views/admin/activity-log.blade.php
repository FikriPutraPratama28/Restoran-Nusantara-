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

    {{-- Total Log --}}
    <div class="bg-admin-card rounded-2xl p-5 relative overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(139,92,246,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#a78bfa" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(139,92,246,0.1); color: #a78bfa;">
                Total Log
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 24px; letter-spacing: -0.02em;">
            {{ number_format($stats['total']) }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Seluruh Riwayat</div>
    </div>

    {{-- Aktivitas Hari Ini --}}
    <div class="bg-admin-card rounded-2xl p-5 relative overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(52,211,153,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(52,211,153,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#34d399" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(52,211,153,0.1); color: #34d399;">
                Hari Ini
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 24px; letter-spacing: -0.02em;">
            {{ number_format($stats['today']) }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Aktivitas Terkini</div>
    </div>

    {{-- Modul Aktif --}}
    <div class="bg-admin-card rounded-2xl p-5 relative overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(56,189,248,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(56,189,248,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#38bdf8" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(56,189,248,0.1); color: #38bdf8;">
                Modul Sistem
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 24px; letter-spacing: -0.02em;">
            {{ count($stats['modules']) }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Kategori Modul</div>
    </div>

    {{-- Hasil Filter --}}
    <div class="bg-admin-card rounded-2xl p-5 relative overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(251,191,36,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(251,191,36,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#fbbf24" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(251,191,36,0.1); color: #fbbf24;">
                Baris Filter
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 24px; letter-spacing: -0.02em;">
            {{ number_format($logs->total()) }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Log Terfilter</div>
    </div>
</div>

{{-- Distribusi per Modul --}}
@if(!empty($stats['modules']))
<div class="bg-admin-card rounded-2xl p-5 mb-5" style="border: 1px solid rgba(255,255,255,0.07);">
    <h3 class="font-bold text-slate-100 mb-4 text-xs tracking-wider uppercase font-jakarta">Distribusi per Modul</h3>
    <div class="flex flex-wrap gap-2">
        @foreach($stats['modules'] as $mod => $cnt)
        @php $color = $moduleColors[$mod] ?? 'from-gray-500 to-slate-600'; @endphp
        <a href="{{ request()->fullUrlWithQuery(['module' => $mod, 'page' => 1]) }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-gradient-to-r {{ $color }} text-white shadow hover:opacity-90 transition-all font-jakarta">
            {{ $mod }} <span class="bg-white/20 px-1.5 py-0.5 rounded-lg">{{ $cnt }}</span>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- Filter --}}
<div class="bg-admin-card rounded-2xl p-4 mb-5" style="border: 1px solid rgba(255,255,255,0.07);">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari aktivitas atau nama..."
            class="flex-1 min-w-[180px] px-4 py-2 rounded-xl border bg-slate-900 border-slate-700 text-xs text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-violet-500 outline-none">
        
        <select name="module" class="px-3 py-2 rounded-xl border bg-slate-900 border-slate-700 text-xs text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="">Semua Modul</option>
            @foreach($modules as $m)
            <option value="{{ $m }}" {{ $module === $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
        
        <select name="role" class="px-3 py-2 rounded-xl border bg-slate-900 border-slate-700 text-xs text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="">Semua Role</option>
            @foreach($roles as $r)
            <option value="{{ $r }}" {{ $role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        
        <input type="date" name="date" value="{{ $date }}"
            class="px-3 py-2 rounded-xl border bg-slate-900 border-slate-700 text-xs text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
        
        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-xl transition-all font-jakarta uppercase tracking-wider">Filter</button>
        
        @if($search || $module || $role || $date)
        <a href="{{ route('admin.activity-log') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all font-jakarta uppercase tracking-wider">Reset</a>
        @endif
    </form>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="bg-emerald-950/20 border border-emerald-500/20 rounded-2xl p-4 mb-4 flex items-center gap-3">
    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
</div>
@endif

{{-- Log Table --}}
<div class="bg-admin-card rounded-2xl shadow-sm overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07);">
    <div class="flex items-center justify-between px-6 py-4 border-b" style="border-bottom-color: rgba(255,255,255,0.06);">
        <div>
            <h3 class="font-bold text-slate-100 font-jakarta text-sm">Log Aktivitas</h3>
            <p class="text-xs text-slate-500 mt-0.5">{{ $logs->total() }} entri ditemukan</p>
        </div>
        {{-- Clear old logs --}}
        @if(auth()->user()->hasPermission('clear_activity_log'))
        <form method="POST" action="{{ route('admin.activity-log.clear') }}"
            onsubmit="return confirm('Hapus log lebih dari 30 hari yang lalu?')">
            @csrf @method('DELETE')
            <input type="hidden" name="days" value="30">
            <button type="submit"
                class="flex items-center gap-2 px-4 py-2 bg-red-950/30 border border-red-500/20 hover:bg-red-900/20 text-red-400 text-xs font-bold rounded-xl transition-all font-jakarta">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Bersihkan Log Lama
            </button>
        </form>
        @endif
    </div>

    @if($logs->isEmpty())
    <div class="py-16 text-center">
        <div class="w-16 h-16 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border" style="border-color: rgba(255,255,255,0.06);">
            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-300 mb-1 font-jakarta">Tidak ada log</h3>
        <p class="text-slate-500 text-xs">Belum ada aktivitas yang tercatat atau filter tidak menemukan hasil</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-xs font-semibold text-slate-400 uppercase tracking-wider text-left border-b" style="background: rgba(255,255,255,0.02); border-bottom-color: rgba(255,255,255,0.06);">
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Aktor</th>
                    <th class="px-4 py-3">Modul</th>
                    <th class="px-4 py-3">Aktivitas</th>
                    <th class="px-4 py-3 hidden lg:table-cell">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color: rgba(255,255,255,0.06);">
                @foreach($logs as $log)
                <tr class="hover:bg-white/5 transition-colors">
                    {{-- Waktu --}}
                    <td class="px-4 py-3.5 whitespace-nowrap">
                        <div class="text-xs font-semibold text-slate-200">{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-[11px] text-slate-500 font-mono">{{ $log->created_at->format('H:i:s') }}</div>
                        <div class="text-[10px] text-slate-600 mt-0.5 font-medium">{{ $log->created_at->diffForHumans() }}</div>
                    </td>
                    {{-- Aktor --}}
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            @if($log->user)
                            <img src="{{ $log->user->avatar_url }}" class="w-7 h-7 rounded-lg object-cover flex-shrink-0 border" style="border-color: rgba(255,255,255,0.1);">
                            @else
                            <div class="w-7 h-7 bg-slate-800 rounded-lg flex items-center justify-center flex-shrink-0 border" style="border-color: rgba(255,255,255,0.06);">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <div class="text-[13px] font-semibold text-slate-200">{{ $log->actor ?? 'System' }}</div>
                                <span class="text-[9.5px] font-bold px-1.5 py-0.5 rounded-full capitalize border
                                    {{ $log->role === 'admin' ? 'bg-violet-950/30 text-violet-400 border-violet-500/10' :
                                       ($log->role === 'karyawan' ? 'bg-blue-950/30 text-blue-400 border-blue-500/10' :
                                       ($log->role === 'pelanggan' ? 'bg-emerald-950/30 text-emerald-400 border-emerald-500/10' :
                                       'bg-slate-800 text-slate-400 border-slate-700')) }}">
                                    {{ $log->role ?? 'system' }}
                                </span>
                            </div>
                        </div>
                    </td>
                    {{-- Modul --}}
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full {{ $log->color }}">
                            {{-- SVG Action Icon mapping --}}
                            @switch($log->icon)
                                @case('login')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    @break
                                @case('logout')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    @break
                                @case('create')
                                @case('register')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    @break
                                @case('edit')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    @break
                                @case('delete')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    @break
                                @case('stock')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @break
                                @case('checkin')
                                @case('checkout')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="12" r="1.5"/></svg>
                                    @break
                                @case('late')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @break
                                @case('leave')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @break
                                @case('approve')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @break
                                @case('reject')
                                @case('cancel')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @break
                                @case('promo')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    @break
                                @case('image')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @break
                                @case('document')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @break
                                @default
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            @endswitch
                            {{ $log->module }}
                        </span>
                    </td>
                    {{-- Deskripsi --}}
                    <td class="px-4 py-3.5 max-w-xs">
                        <p class="text-xs text-slate-300 leading-relaxed font-medium">{{ $log->description }}</p>
                        @if($log->properties)
                        <details class="mt-1">
                            <summary class="text-xs text-violet-400 cursor-pointer hover:text-violet-300">Detail data</summary>
                            <pre class="text-[10px] text-slate-400 mt-1 bg-slate-900 border border-slate-800 p-2.5 rounded-lg overflow-x-auto">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </details>
                        @endif
                    </td>
                    {{-- IP --}}
                    <td class="px-4 py-3.5 hidden lg:table-cell">
                        <span class="text-xs font-mono text-slate-500 font-medium">{{ $log->ip_address ?? '—' }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t font-jakarta" style="border-top-color: rgba(255,255,255,0.06);">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection

