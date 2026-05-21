@extends('karyawan.layouts.app')
@section('title', 'Jadwal Kerja')
@section('page-title', 'Jadwal Kerja')
@section('page-subtitle', 'Kalender absensi bulanan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Info Shift --}}
    <div class="bg-gradient-to-br from-violet-600 to-blue-700 rounded-2xl p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-violet-200 text-sm mb-1">Shift Kerja Anda</p>
                <h2 class="text-xl font-bold">{{ $employee->shift_label }}</h2>
                <p class="text-violet-200 text-sm mt-1">{{ $employee->jabatan }}</p>
            </div>
            <div class="text-5xl">📅</div>
        </div>
    </div>

    {{-- Navigasi Bulan --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700">
        <form method="GET" class="flex items-center justify-between gap-3">
            @php
            $prevMonth = \Carbon\Carbon::create($year, $month, 1)->subMonth();
            $nextMonth = \Carbon\Carbon::create($year, $month, 1)->addMonth();
            @endphp
            <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}"
                class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h3 class="font-bold text-gray-900 dark:text-white text-lg">
                {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
            </h3>
            <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}"
                class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </form>
    </div>

    {{-- Kalender --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        {{-- Header hari --}}
        <div class="grid grid-cols-7 border-b border-gray-100 dark:border-slate-700">
            @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $day)
            <div class="py-3 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider {{ $day === 'Min' || $day === 'Sab' ? 'text-red-400 dark:text-red-500' : '' }}">
                {{ $day }}
            </div>
            @endforeach
        </div>

        {{-- Grid kalender --}}
        @php
        $firstDay = \Carbon\Carbon::create($year, $month, 1)->dayOfWeek; // 0=Sun
        @endphp
        <div class="grid grid-cols-7">
            {{-- Padding awal --}}
            @for($i = 0; $i < $firstDay; $i++)
            <div class="h-20 border-b border-r border-gray-50 dark:border-slate-700/50"></div>
            @endfor

            @foreach($calendar as $day)
            @php
            $att = $day['attendance'];
            $sb  = $att ? $att->status_badge : null;
            @endphp
            <div class="h-20 border-b border-r border-gray-50 dark:border-slate-700/50 p-1.5 relative
                {{ $day['isToday'] ? 'bg-violet-50 dark:bg-violet-900/20' : '' }}
                {{ $day['isWeekend'] ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                <div class="flex items-start justify-between mb-1">
                    <span class="text-xs font-bold {{ $day['isToday'] ? 'w-6 h-6 bg-violet-600 text-white rounded-full flex items-center justify-center' : ($day['isWeekend'] ? 'text-red-400 dark:text-red-500' : 'text-gray-700 dark:text-slate-300') }}">
                        {{ $day['date']->day }}
                    </span>
                </div>
                @if($att)
                <div class="space-y-0.5">
                    <div class="text-[10px] font-mono text-gray-500 dark:text-slate-400 leading-tight">
                        {{ $att->check_in ? substr($att->check_in, 0, 5) : '' }}
                    </div>
                    <span class="inline-block text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $sb['class'] }}">
                        {{ $sb['label'] }}
                    </span>
                </div>
                @elseif(!$day['isWeekend'] && $day['date']->isPast() && !$day['isToday'])
                <div class="text-[10px] text-red-400 dark:text-red-500 font-semibold">Alpha</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Legend --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700">
        <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 mb-3 uppercase tracking-wider">Keterangan</p>
        <div class="flex flex-wrap gap-3">
            @foreach([
                ['label'=>'Hadir',     'class'=>'bg-emerald-100 text-emerald-700'],
                ['label'=>'Terlambat', 'class'=>'bg-yellow-100 text-yellow-700'],
                ['label'=>'Izin',      'class'=>'bg-blue-100 text-blue-700'],
                ['label'=>'Sakit',     'class'=>'bg-orange-100 text-orange-700'],
                ['label'=>'Alpha',     'class'=>'bg-red-100 text-red-700'],
            ] as $l)
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $l['class'] }}">{{ $l['label'] }}</span>
            @endforeach
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-violet-100 text-violet-700">Hari Ini</span>
        </div>
    </div>

    {{-- Statistik Bulan --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-4">
            Rekap {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
        </p>
        <div class="grid grid-cols-5 gap-3">
            @foreach([
                ['label'=>'Hadir',     'val'=>$monthStats['hadir'],     'color'=>'text-emerald-600', 'bg'=>'bg-emerald-50 dark:bg-emerald-900/20'],
                ['label'=>'Terlambat', 'val'=>$monthStats['terlambat'], 'color'=>'text-yellow-600',  'bg'=>'bg-yellow-50 dark:bg-yellow-900/20'],
                ['label'=>'Izin',      'val'=>$monthStats['izin'],      'color'=>'text-blue-600',    'bg'=>'bg-blue-50 dark:bg-blue-900/20'],
                ['label'=>'Sakit',     'val'=>$monthStats['sakit'],     'color'=>'text-orange-600',  'bg'=>'bg-orange-50 dark:bg-orange-900/20'],
                ['label'=>'Alpha',     'val'=>$monthStats['alpha'],     'color'=>'text-red-600',     'bg'=>'bg-red-50 dark:bg-red-900/20'],
            ] as $s)
            <div class="rounded-xl p-3 text-center {{ $s['bg'] }}">
                <div class="text-xl font-bold {{ $s['color'] }}">{{ $s['val'] }}</div>
                <div class="text-[10px] text-gray-500 dark:text-slate-400 mt-0.5">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
