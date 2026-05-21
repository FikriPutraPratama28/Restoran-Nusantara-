@extends('admin.layouts.app')
@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi')
@section('page-subtitle', 'Monitor kehadiran karyawan harian')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    @php
    $cards = [
        ['label'=>'Total Karyawan', 'val'=>$stats['total'],     'icon'=>'👥', 'color'=>'from-violet-500 to-purple-600'],
        ['label'=>'Hadir',          'val'=>$stats['hadir'],     'icon'=>'✅', 'color'=>'from-emerald-500 to-teal-600'],
        ['label'=>'Terlambat',      'val'=>$stats['terlambat'], 'icon'=>'⏰', 'color'=>'from-yellow-500 to-amber-600'],
        ['label'=>'Izin',           'val'=>$stats['izin'],      'icon'=>'📋', 'color'=>'from-blue-500 to-cyan-600'],
        ['label'=>'Sakit',          'val'=>$stats['sakit'],     'icon'=>'🤒', 'color'=>'from-orange-500 to-red-500'],
        ['label'=>'Alpha',          'val'=>$stats['alpha'],     'icon'=>'⛔', 'color'=>'from-red-500 to-rose-600'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-9 h-9 bg-gradient-to-br {{ $c['color'] }} rounded-xl flex items-center justify-center text-base shadow mb-2">{{ $c['icon'] }}</div>
        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $c['val'] }}</div>
        <div class="text-xs text-gray-500 dark:text-slate-400">{{ $c['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Filter Tanggal --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 mb-4">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <label class="text-sm font-semibold text-gray-700 dark:text-slate-300">Tanggal:</label>
        <input type="date" name="date" value="{{ $date }}"
            class="px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">Tampilkan</button>
        <a href="{{ route('admin.attendance.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all">Hari Ini</a>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Yang Sudah Absen --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-white">✅ Sudah Absen</h3>
            <span class="text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 px-2.5 py-1 rounded-full">{{ $attendances->count() }} orang</span>
        </div>
        @if($attendances->isEmpty())
        <div class="py-10 text-center text-gray-400 dark:text-slate-500 text-sm">Belum ada yang absen</div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($attendances as $att)
            @php $sb = $att->status_badge; @endphp
            <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <img src="{{ $att->employee->user->avatar_url }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm truncate">{{ $att->employee->user->name }}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500">{{ $att->employee->jabatan }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <div class="flex items-center gap-2 justify-end mb-1">
                        <span class="text-xs font-mono text-gray-600 dark:text-slate-400">
                            {{ $att->check_in ?? '—' }} → {{ $att->check_out ?? 'Belum CO' }}
                        </span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $sb['class'] }}">{{ $sb['label'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Yang Belum Absen --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-white">⛔ Belum Absen</h3>
            <span class="text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 px-2.5 py-1 rounded-full">{{ $absentEmployees->count() }} orang</span>
        </div>
        @if($absentEmployees->isEmpty())
        <div class="py-10 text-center text-gray-400 dark:text-slate-500 text-sm">🎉 Semua karyawan sudah absen!</div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($absentEmployees as $emp)
            <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <img src="{{ $emp->user->avatar_url }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm truncate">{{ $emp->user->name }}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500">{{ $emp->jabatan }} · {{ ucfirst($emp->shift) }}</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 flex-shrink-0">Alpha</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
