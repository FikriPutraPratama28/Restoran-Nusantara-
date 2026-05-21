@extends('admin.layouts.app')
@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Karyawan')
@section('page-subtitle', 'Profil & riwayat absensi karyawan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-gray-500 dark:text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 text-sm font-medium transition-colors group">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
        Kembali
    </a>

    {{-- Profile Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <img src="{{ $employee->user->avatar_url }}" alt="{{ $employee->user->name }}"
                class="w-20 h-20 rounded-2xl object-cover border-4 border-violet-100 dark:border-violet-900/30 flex-shrink-0">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $employee->user->name }}</h2>
                    <span class="font-mono text-xs font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20 px-2 py-1 rounded-lg">{{ $employee->employee_code }}</span>
                    @php $badge = $employee->status_badge; @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </div>
                <p class="text-gray-500 dark:text-slate-400 text-sm">{{ $employee->jabatan }} · {{ $employee->shift_label }}</p>
                <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-500 dark:text-slate-400">
                    <span>📧 {{ $employee->user->email }}</span>
                    @if($employee->user->phone)<span>📱 {{ $employee->user->phone }}</span>@endif
                    <span>📅 Bergabung {{ $employee->join_date->format('d M Y') }}</span>
                </div>
            </div>
            <a href="{{ route('admin.employees.edit', $employee) }}"
                class="flex items-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
        </div>
    </div>

    {{-- Statistik Absensi Bulan Ini --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        @php
        $statItems = [
            ['label'=>'Hadir',     'val'=>$attendanceStats['hadir'],     'color'=>'text-emerald-600', 'bg'=>'bg-emerald-50 dark:bg-emerald-900/20'],
            ['label'=>'Terlambat', 'val'=>$attendanceStats['terlambat'], 'color'=>'text-yellow-600',  'bg'=>'bg-yellow-50 dark:bg-yellow-900/20'],
            ['label'=>'Izin',      'val'=>$attendanceStats['izin'],      'color'=>'text-blue-600',    'bg'=>'bg-blue-50 dark:bg-blue-900/20'],
            ['label'=>'Sakit',     'val'=>$attendanceStats['sakit'],     'color'=>'text-orange-600',  'bg'=>'bg-orange-50 dark:bg-orange-900/20'],
            ['label'=>'Alpha',     'val'=>$attendanceStats['alpha'],     'color'=>'text-red-600',     'bg'=>'bg-red-50 dark:bg-red-900/20'],
        ];
        @endphp
        @foreach($statItems as $s)
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 text-center {{ $s['bg'] }}">
            <div class="text-2xl font-bold {{ $s['color'] }}">{{ $s['val'] }}</div>
            <div class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filter Bulan --}}
    <form method="GET" class="flex items-center gap-3">
        <select name="month" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
            @endforeach
        </select>
        <select name="year" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            @foreach(range(date('Y')-2, date('Y')) as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">Tampilkan</button>
    </form>

    {{-- Tabel Absensi --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Absensi</h3>
        </div>
        @if($attendances->isEmpty())
        <div class="py-12 text-center text-gray-400 dark:text-slate-500">
            <div class="text-4xl mb-3">📋</div>
            <p class="text-sm">Belum ada data absensi bulan ini</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50">
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Check In</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Check Out</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Durasi</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase tracking-wider">Terlambat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @foreach($attendances as $att)
                    @php $sb = $att->status_badge; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-3.5 text-sm font-medium text-gray-800 dark:text-slate-200">
                            {{ $att->date->locale('id')->isoFormat('ddd, D MMM YYYY') }}
                        </td>
                        <td class="px-4 py-3.5 text-sm text-gray-600 dark:text-slate-400 font-mono">
                            {{ $att->check_in ?? '—' }}
                            @if($att->check_in_photo_url)
                            <a href="{{ $att->check_in_photo_url }}" target="_blank" class="ml-1 text-violet-500 hover:text-violet-700 text-xs">📷</a>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-sm text-gray-600 dark:text-slate-400 font-mono">
                            {{ $att->check_out ?? '—' }}
                            @if($att->check_out_photo_url)
                            <a href="{{ $att->check_out_photo_url }}" target="_blank" class="ml-1 text-violet-500 hover:text-violet-700 text-xs">📷</a>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-sm text-gray-600 dark:text-slate-400">{{ $att->work_duration ?? '—' }}</td>
                        <td class="px-4 py-3.5">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sb['class'] }}">{{ $sb['label'] }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-sm text-gray-500 dark:text-slate-400">
                            {{ $att->late_minutes > 0 ? $att->late_minutes . ' mnt' : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
