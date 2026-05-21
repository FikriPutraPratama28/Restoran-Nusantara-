@extends('karyawan.layouts.app')
@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
@php
    $leaveQuota   = 12;
    $pendingLeave = $employee->leaveRequests()->where('status','menunggu')->first();
    $shiftTimes   = ['pagi'=>'06:00–14:00','siang'=>'14:00–22:00','malam'=>'22:00–06:00','full'=>'08:00–17:00'];
    $shiftIcons   = ['pagi'=>'🌅','siang'=>'☀️','malam'=>'🌙','full'=>'🕐'];
    $shiftColors  = ['pagi'=>'from-amber-500 to-orange-600','siang'=>'from-blue-500 to-cyan-600','malam'=>'from-violet-600 to-indigo-700','full'=>'from-emerald-500 to-teal-600'];
    $shiftKey     = $employee->shift ?? 'full';
    $usedLeave    = $leaveQuota - $remainLeave;
    $notifications = \App\Models\Notification::forUser(auth()->id())->latest()->take(5)->get();
@endphp
<div class="space-y-5 max-w-5xl mx-auto">

{{-- ===== GREETING + STATUS ABSENSI ===== --}}
<div class="bg-gradient-to-br from-blue-600 via-blue-700 to-violet-700 rounded-2xl p-6 text-white relative overflow-hidden shadow-xl shadow-blue-600/30">
    <div class="absolute top-0 right-0 w-56 h-56 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-36 h-36 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex-1">
            <p class="text-blue-200 text-sm mb-1">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            <h2 class="text-2xl font-bold mb-1">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
            <p class="text-blue-200 text-sm">{{ $employee->jabatan }} &nbsp;·&nbsp; {{ $employee->shift_label }}</p>
            <div class="flex items-center gap-2 mt-3">
                @php $sb = $employee->status_badge; @endphp
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-white/20 text-white">{{ $sb['label'] }}</span>
                <span class="text-xs text-blue-200">{{ $employee->employee_code }}</span>
            </div>
        </div>
        {{-- Status Absensi Hari Ini --}}
        <div class="flex-shrink-0">
            @if($todayAttend)
                @if($todayAttend->check_out)
                <div class="bg-white/15 backdrop-blur-sm rounded-2xl px-5 py-4 text-center border border-white/20 min-w-[160px]">
                    <p class="text-xs text-blue-200 mb-1">✅ Selesai Hari Ini</p>
                    <p class="font-bold text-xl font-mono">{{ substr($todayAttend->check_in,0,5) }} – {{ substr($todayAttend->check_out,0,5) }}</p>
                    <p class="text-xs text-blue-200 mt-1">⏱ {{ $todayAttend->work_duration ?? '-' }}</p>
                    @if($todayAttend->status === 'terlambat')
                    <p class="text-xs text-yellow-300 mt-1">⏰ Terlambat {{ $todayAttend->late_minutes }} menit</p>
                    @endif
                </div>
                @else
                <div class="bg-white/15 backdrop-blur-sm rounded-2xl px-5 py-4 text-center border border-white/20 min-w-[160px]">
                    <p class="text-xs text-blue-200 mb-1">📍 Sudah Check In</p>
                    <p class="font-bold text-2xl font-mono">{{ substr($todayAttend->check_in,0,5) }}</p>
                    <p class="text-xs text-yellow-300 mt-1">Belum check out</p>
                    @if($todayAttend->status === 'terlambat')
                    <p class="text-xs text-red-300 mt-0.5">⏰ Terlambat {{ $todayAttend->late_minutes }}m</p>
                    @endif
                </div>
                @endif
            @else
            <div class="bg-white/15 backdrop-blur-sm rounded-2xl px-5 py-4 text-center border border-white/20 min-w-[160px]">
                <p class="text-xs text-blue-200 mb-1">⚠️ Belum Absen</p>
                <p class="font-bold text-lg">--:--</p>
                <p class="text-xs text-yellow-300 mt-1">Segera absen!</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ===== TOMBOL ABSEN CEPAT ===== --}}
@if(!$todayAttend || !$todayAttend->check_out)
<form method="POST" action="{{ !$todayAttend ? route('karyawan.attendance.checkin.web') : route('karyawan.attendance.checkout.web') }}">
    @csrf
    <button type="submit"
        class="block w-full py-5 rounded-2xl font-bold text-xl text-white text-center transition-all shadow-xl hover:-translate-y-0.5 active:translate-y-0
        {{ !$todayAttend ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/40' : 'bg-blue-500 hover:bg-blue-600 shadow-blue-500/40' }}">
        {{ !$todayAttend ? '✅  CHECK IN SEKARANG' : '🏠  CHECK OUT SEKARANG' }}
    </button>
</form>
@endif

{{-- ===== STATISTIK BULAN INI ===== --}}
<div>
    <h3 class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Statistik Bulan Ini — {{ now()->locale('id')->isoFormat('MMMM YYYY') }}</h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @php
        $statCards = [
            ['label'=>'Hadir',     'val'=>$stats['hadir'],     'icon'=>'✅', 'color'=>'text-emerald-600 dark:text-emerald-400', 'bg'=>'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800'],
            ['label'=>'Terlambat', 'val'=>$stats['terlambat'], 'icon'=>'⏰', 'color'=>'text-yellow-600 dark:text-yellow-400',  'bg'=>'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-100 dark:border-yellow-800'],
            ['label'=>'Izin',      'val'=>$stats['izin'],      'icon'=>'📋', 'color'=>'text-blue-600 dark:text-blue-400',      'bg'=>'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800'],
            ['label'=>'Sakit',     'val'=>$stats['sakit'],     'icon'=>'🤒', 'color'=>'text-orange-600 dark:text-orange-400',  'bg'=>'bg-orange-50 dark:bg-orange-900/20 border-orange-100 dark:border-orange-800'],
        ];
        @endphp
        @foreach($statCards as $s)
        <div class="rounded-2xl p-4 border {{ $s['bg'] }} text-center">
            <div class="text-2xl mb-1">{{ $s['icon'] }}</div>
            <div class="text-3xl font-bold {{ $s['color'] }}">{{ $s['val'] }}</div>
            <div class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-medium">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== JADWAL KERJA + SISA CUTI ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    {{-- Jadwal Kerja --}}
    <div class="bg-gradient-to-br {{ $shiftColors[$shiftKey] }} rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-28 h-28 bg-white/10 rounded-full -translate-y-1/3 translate-x-1/3 pointer-events-none"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-2xl">{{ $shiftIcons[$shiftKey] }}</span>
                <span class="text-sm font-bold uppercase tracking-wider opacity-80">Jadwal Kerja</span>
            </div>
            <p class="text-3xl font-bold mb-1">{{ $shiftTimes[$shiftKey] }}</p>
            <p class="text-sm opacity-80 capitalize">Shift {{ ucfirst($shiftKey) }} &nbsp;·&nbsp; {{ $employee->jabatan }}</p>
            <a href="{{ route('karyawan.schedule') }}"
                class="inline-flex items-center gap-1 mt-4 text-xs font-bold bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-xl transition-all">
                Lihat Kalender →
            </a>
        </div>
    </div>

    {{-- Sisa Cuti Tahunan --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-xl">🏖️</span>
            <span class="text-sm font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider">Kuota Cuti Tahunan</span>
        </div>
        <div class="flex items-end gap-3 mb-3">
            <span class="text-5xl font-bold text-blue-600 dark:text-blue-400">{{ $remainLeave }}</span>
            <span class="text-gray-400 dark:text-slate-500 text-sm mb-1">/ {{ $leaveQuota }} hari tersisa</span>
        </div>
        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2.5 mb-3">
            <div class="h-2.5 rounded-full {{ $remainLeave > 6 ? 'bg-emerald-500' : ($remainLeave > 3 ? 'bg-yellow-500' : 'bg-red-500') }} transition-all"
                style="width: {{ ($remainLeave / $leaveQuota) * 100 }}%"></div>
        </div>
        <p class="text-xs text-gray-400 dark:text-slate-500">Terpakai: {{ $usedLeave }} hari · Tahun {{ now()->year }}</p>
        @if($pendingLeave)
        <div class="mt-3 flex items-center gap-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl px-3 py-2">
            <span class="text-yellow-500 text-sm">⏳</span>
            <p class="text-xs text-yellow-700 dark:text-yellow-400 font-medium">Ada pengajuan cuti menunggu persetujuan</p>
        </div>
        @endif
    </div>
</div>

{{-- ===== JADWAL MINGGUAN (jika ada data jadwal_kerja) ===== --}}
@if($jadwalKerja->isNotEmpty())
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center gap-2">
            <span class="text-lg">🗓️</span>
            <h3 class="font-bold text-gray-900 dark:text-white text-sm">Jadwal Kerja Mingguan</h3>
        </div>
        <a href="{{ route('karyawan.schedule') }}" class="text-blue-600 dark:text-blue-400 text-xs font-semibold hover:underline">Kalender →</a>
    </div>
    <div class="grid grid-cols-3 sm:grid-cols-7 divide-x divide-gray-100 dark:divide-slate-700">
        @php
        $hariList = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
        $hariShort = ['senin'=>'Sen','selasa'=>'Sel','rabu'=>'Rab','kamis'=>'Kam','jumat'=>'Jum','sabtu'=>'Sab','minggu'=>'Min'];
        $jadwalMap = $jadwalKerja->keyBy('hari');
        $todayHari = strtolower(now()->locale('id')->isoFormat('dddd'));
        @endphp
        @foreach($hariList as $hari)
        @php $j = $jadwalMap->get($hari); $isToday = $todayHari === $hari; @endphp
        <div class="p-3 text-center {{ $isToday ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
            <p class="text-xs font-bold {{ $isToday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-slate-400' }} mb-1">
                {{ $hariShort[$hari] }}
                @if($isToday)<span class="block w-1.5 h-1.5 bg-blue-500 rounded-full mx-auto mt-0.5"></span>@endif
            </p>
            @if($j)
            <p class="text-[10px] font-semibold text-gray-700 dark:text-slate-300">{{ $j->jam_mulai_display }}</p>
            <p class="text-[10px] text-gray-400 dark:text-slate-500">{{ $j->jam_selesai_display }}</p>
            <p class="text-[9px] mt-1 capitalize text-gray-400 dark:text-slate-500">{{ ucfirst($j->shift) }}</p>
            @else
            <p class="text-[10px] text-gray-300 dark:text-slate-600 mt-2">—</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== RIWAYAT ABSENSI + PENGAJUAN CUTI ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Riwayat Absensi 7 Hari --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-2">
                <span class="text-lg">📅</span>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Riwayat Absensi</h3>
                <span class="text-xs text-gray-400 dark:text-slate-500">7 hari terakhir</span>
            </div>
            <a href="{{ route('karyawan.attendance') }}" class="text-blue-600 dark:text-blue-400 text-xs font-semibold hover:underline">Lihat semua →</a>
        </div>
        @if($recentAttend->isEmpty())
        <div class="py-12 text-center">
            <div class="text-4xl mb-3">📋</div>
            <p class="text-gray-400 dark:text-slate-500 text-sm">Belum ada data absensi</p>
        </div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($recentAttend as $att)
            @php $sb = $att->status_badge; @endphp
            <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <div class="w-11 h-11 bg-gray-100 dark:bg-slate-700 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300 leading-none">{{ $att->date->format('d') }}</span>
                    <span class="text-[10px] text-gray-400 dark:text-slate-500 uppercase">{{ $att->date->format('M') }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">
                        {{ $att->date->locale('id')->isoFormat('ddd, D MMM') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 font-mono">
                        {{ $att->check_in ? substr($att->check_in,0,5) : '—' }}
                        →
                        {{ $att->check_out ? substr($att->check_out,0,5) : 'Belum CO' }}
                        @if($att->work_duration) &nbsp;·&nbsp; ⏱ {{ $att->work_duration }} @endif
                    </p>
                    @if($att->late_minutes > 0)
                    <p class="text-[10px] text-yellow-500 mt-0.5">⏰ Terlambat {{ $att->late_minutes }} menit</p>
                    @endif
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $sb['class'] }} flex-shrink-0">{{ $sb['label'] }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Pengajuan Cuti --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-2">
                <span class="text-lg">🏖️</span>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Pengajuan Cuti</h3>
            </div>
            <a href="{{ route('karyawan.leave') }}" class="text-blue-600 dark:text-blue-400 text-xs font-semibold hover:underline">Ajukan →</a>
        </div>
        @if($leaveRequests->isEmpty())
        <div class="py-12 text-center">
            <div class="text-4xl mb-3">🏖️</div>
            <p class="text-gray-400 dark:text-slate-500 text-sm mb-4">Belum ada pengajuan cuti</p>
            <a href="{{ route('karyawan.leave') }}"
                class="inline-block px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-600/30">
                Ajukan Cuti Sekarang
            </a>
        </div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($leaveRequests as $leave)
            @php $sb = $leave->status_badge; @endphp
            <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                    {{ $leave->status === 'disetujui' ? 'bg-emerald-100 dark:bg-emerald-900/30' : ($leave->status === 'ditolak' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-yellow-100 dark:bg-yellow-900/30') }}">
                    {{ str_contains($leave->type,'sakit') ? '🤒' : (str_contains($leave->type,'izin') ? '📋' : (str_contains($leave->type,'khusus') ? '⭐' : '🏖️')) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $leave->type_label }}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500">
                        {{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}
                        &nbsp;·&nbsp; {{ $leave->total_days }} hari
                    </p>
                    @if($leave->admin_notes)
                    <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-0.5 italic truncate">💬 {{ $leave->admin_notes }}</p>
                    @endif
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $sb['class'] }} flex-shrink-0">{{ $sb['label'] }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ===== NOTIFIKASI ===== --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center gap-2">
            <span class="text-lg">🔔</span>
            <h3 class="font-bold text-gray-900 dark:text-white text-sm">Notifikasi Terbaru</h3>
            @if($_unreadCount > 0)
            <span class="text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-2 py-0.5 rounded-full">
                {{ $_unreadCount }} baru
            </span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if($_unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs text-violet-600 dark:text-violet-400 hover:underline font-medium">
                    Tandai semua dibaca
                </button>
            </form>
            @endif
            <a href="{{ route('notifications.index') }}" class="text-blue-600 dark:text-blue-400 text-xs font-semibold hover:underline">Lihat semua →</a>
        </div>
    </div>
    @if($notifications->isEmpty())
    <div class="py-10 text-center">
        <div class="text-4xl mb-3">🔔</div>
        <p class="text-gray-400 dark:text-slate-500 text-sm">Tidak ada notifikasi</p>
    </div>
    @else
    <div class="divide-y divide-gray-100 dark:divide-slate-700">
        @foreach($notifications as $notif)
        <div class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors {{ $notif->isUnread() ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
            <div class="w-10 h-10 {{ $notif->color }} rounded-xl flex items-center justify-center text-lg flex-shrink-0 mt-0.5">
                {{ $notif->icon }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-semibold text-gray-800 dark:text-slate-200 leading-tight">{{ $notif->title }}</p>
                    @if($notif->isUnread())
                    <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
            @if($notif->url)
            <a href="{{ $notif->url }}"
                onclick="fetch('{{ route('notifications.read', $notif) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}})"
                class="flex-shrink-0 p-1.5 text-violet-500 hover:text-violet-700 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-lg transition-all mt-0.5" title="Buka">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ===== AKSI CEPAT ===== --}}
<div>
    <h3 class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Aksi Cepat</h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @php
        $actions = [
            ['href'=>route('karyawan.attendance'), 'icon'=>'📍', 'label'=>'Absen',       'sub'=>'Check in / out',  'color'=>'from-blue-500 to-blue-700'],
            ['href'=>route('karyawan.schedule'),   'icon'=>'📅', 'label'=>'Jadwal',      'sub'=>'Kalender kerja',  'color'=>'from-violet-500 to-violet-700'],
            ['href'=>route('karyawan.leave'),      'icon'=>'🏖️', 'label'=>'Ajukan Cuti', 'sub'=>$remainLeave.' hari tersisa', 'color'=>'from-orange-500 to-orange-700'],
            ['href'=>route('profile'),             'icon'=>'👤', 'label'=>'Profil',      'sub'=>'Edit data diri',  'color'=>'from-emerald-500 to-emerald-700'],
        ];
        @endphp
        @foreach($actions as $a)
        <a href="{{ $a['href'] }}"
            class="bg-gradient-to-br {{ $a['color'] }} rounded-2xl p-4 text-white hover:opacity-90 hover:-translate-y-0.5 transition-all shadow-lg group">
            <div class="text-3xl mb-2">{{ $a['icon'] }}</div>
            <div class="text-sm font-bold leading-tight">{{ $a['label'] }}</div>
            <div class="text-xs opacity-70 mt-0.5">{{ $a['sub'] }}</div>
        </a>
        @endforeach
    </div>
</div>

</div>{{-- end .space-y-5 --}}
@endsection
