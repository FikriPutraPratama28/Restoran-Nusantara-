@extends('admin.layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan Lengkap')
@section('page-subtitle', 'Analitik penjualan, reservasi, absensi & cuti')

@section('content')
@php use Carbon\Carbon; @endphp

{{-- ===== FILTER BAR ===== --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 mb-5">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <label class="text-sm font-semibold text-gray-700 dark:text-slate-300">Periode:</label>
        <select name="month" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ Carbon::create(null,$m)->locale('id')->isoFormat('MMMM') }}</option>
            @endforeach
        </select>
        <select name="year" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            @foreach(range(date('Y')-2, date('Y')) as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">Tampilkan</button>
        <span class="text-gray-300 dark:text-slate-600">|</span>
        <span class="text-sm text-gray-500 dark:text-slate-400">Periode: <strong class="text-gray-700 dark:text-slate-300">{{ Carbon::create($year,$month,1)->locale('id')->isoFormat('MMMM YYYY') }}</strong></span>
    </form>
</div>

{{-- ===== TAB NAVIGATION ===== --}}
<div class="flex gap-1 bg-white dark:bg-slate-800 p-1.5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-5 overflow-x-auto">
    @foreach([
        ['key'=>'penjualan', 'icon'=>'🛒', 'label'=>'Penjualan'],
        ['key'=>'reservasi', 'icon'=>'📅', 'label'=>'Reservasi'],
        ['key'=>'absensi',   'icon'=>'📍', 'label'=>'Absensi'],
        ['key'=>'cuti',      'icon'=>'🏖️', 'label'=>'Cuti'],
    ] as $t)
    <a href="{{ request()->fullUrlWithQuery(['tab' => $t['key']]) }}"
        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-semibold whitespace-nowrap transition-all
        {{ $tab === $t['key'] ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/30' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
        <span>{{ $t['icon'] }}</span>
        <span>{{ $t['label'] }}</span>
    </a>
    @endforeach
</div>

{{-- ===== TAB A: PENJUALAN ===== --}}
@if($tab === 'penjualan')

{{-- KPI Penjualan --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    @php
    $kpiSales = [
        ['label'=>'Total Item Terjual', 'value'=>number_format($totalItems),          'icon'=>'🛒', 'color'=>'from-violet-500 to-purple-600', 'sub'=>'semua waktu'],
        ['label'=>'Est. Pendapatan',    'value'=>'Rp '.number_format($totalRevEst,0,',','.'), 'icon'=>'💰', 'color'=>'from-emerald-500 to-teal-600', 'sub'=>'estimasi @Rp35k/item'],
        ['label'=>'Rating Rata-rata',   'value'=>number_format($avgRating,1),          'icon'=>'⭐', 'color'=>'from-yellow-500 to-amber-600', 'sub'=>'dari semua menu'],
        ['label'=>'Menu Stok Habis',    'value'=>$menuHabis,                           'icon'=>'⚠️', 'color'=>'from-red-500 to-rose-600',    'sub'=>'perlu restock'],
    ];
    @endphp
    @foreach($kpiSales as $k)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 bg-gradient-to-br {{ $k['color'] }} rounded-xl flex items-center justify-center text-xl shadow-lg">{{ $k['icon'] }}</div>
        </div>
        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $k['value'] }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">{{ $k['label'] }}</div>
        <div class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ $k['sub'] }}</div>
    </div>
    @endforeach
</div>

{{-- Sub-tab Harian/Mingguan/Bulanan --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-5">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
        <div>
            <h3 class="font-bold text-gray-900 dark:text-white">Grafik Penjualan</h3>
            <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Estimasi berdasarkan sold_count menu</p>
        </div>
        <div class="flex gap-1 bg-gray-100 dark:bg-slate-700 p-1 rounded-xl">
            @foreach(['harian'=>'7 Hari','mingguan'=>'4 Minggu','bulanan'=>'6 Bulan'] as $rKey => $rLabel)
            <a href="{{ request()->fullUrlWithQuery(['range' => $rKey]) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all
                {{ $range === $rKey ? 'bg-white dark:bg-slate-800 shadow text-gray-900 dark:text-white' : 'text-gray-500 dark:text-slate-400' }}">
                {{ $rLabel }}
            </a>
            @endforeach
        </div>
    </div>
    <div class="p-6">
        @php
        $salesData = $range === 'mingguan' ? $salesMingguan : ($range === 'bulanan' ? $salesBulanan : $salesHarian);
        $maxRev = max(array_column($salesData, 'rev') ?: [1]);
        $maxItems = max(array_column($salesData, 'items') ?: [1]);
        @endphp
        <div class="flex items-end gap-3 h-48 mb-4">
            @foreach($salesData as $d)
            @php $pct = $maxRev > 0 ? ($d['rev'] / $maxRev) * 100 : 0; @endphp
            <div class="flex-1 flex flex-col items-center gap-2 group relative">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap z-10 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    {{ 'Rp '.number_format($d['rev'],0,',','.') }}
                </div>
                <div class="w-full flex justify-center items-end" style="height:160px">
                    <div class="w-full rounded-t-lg bg-gradient-to-t from-violet-600 to-violet-400 hover:from-violet-700 hover:to-violet-500 cursor-pointer transition-all duration-300"
                        style="height: {{ max($pct * 1.6, 4) }}px"></div>
                </div>
                <div class="text-center">
                    <div class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ $d['short'] }}</div>
                    <div class="text-[10px] text-gray-400">{{ $d['date'] ?? '' }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-violet-500"></div>
                <span class="text-xs text-gray-500 dark:text-slate-400">Estimasi pendapatan</span>
            </div>
            <div class="text-sm font-bold text-gray-900 dark:text-white">
                Total: <span class="text-violet-600">Rp {{ number_format(array_sum(array_column($salesData,'rev')),0,',','.') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Top Menu Terlaris --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-gray-900 dark:text-white">Top 10 Menu Terlaris</h3>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Berdasarkan total sold_count</p>
    </div>
    @if($topMenus->isEmpty())
    <div class="py-10 text-center text-gray-400 text-sm">Belum ada data menu</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-slate-700/50">
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3 uppercase">#</th>
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Menu</th>
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Kategori</th>
                <th class="text-right text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Terjual</th>
                <th class="text-right text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Est. Pendapatan</th>
                <th class="text-right text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Rating</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($topMenus as $i => $menu)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-3.5 text-sm font-bold text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $menu->image_src }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                            <span class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $menu->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5"><span class="text-xs capitalize text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded-lg">{{ $menu->category }}</span></td>
                    <td class="px-4 py-3.5 text-right text-sm font-bold text-violet-600">{{ number_format($menu->sold_count) }}</td>
                    <td class="px-4 py-3.5 text-right text-sm font-semibold text-emerald-600">Rp {{ number_format($menu->sold_count * $menu->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3.5 text-right text-sm text-yellow-500 font-bold">{{ number_format($menu->rating,1) }} ⭐</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif

{{-- ===== TAB B: RESERVASI ===== --}}
@if($tab === 'reservasi')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    @foreach([
        ['label'=>'Total Reservasi',  'value'=>$reservasiStats['total'],      'icon'=>'📅', 'color'=>'from-blue-500 to-cyan-600'],
        ['label'=>'Terkonfirmasi',    'value'=>$reservasiStats['konfirmasi'],  'icon'=>'✅', 'color'=>'from-emerald-500 to-teal-600'],
        ['label'=>'Menunggu',         'value'=>$reservasiStats['menunggu'],    'icon'=>'⏳', 'color'=>'from-yellow-500 to-amber-600'],
        ['label'=>'Dibatalkan',       'value'=>$reservasiStats['batal'],       'icon'=>'❌', 'color'=>'from-red-500 to-rose-600'],
    ] as $k)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-11 h-11 bg-gradient-to-br {{ $k['color'] }} rounded-xl flex items-center justify-center text-xl shadow-lg mb-3">{{ $k['icon'] }}</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $k['value'] }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">{{ $k['label'] }}</div>
    </div>
    @endforeach
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-slate-700 text-center">
    <div class="text-5xl mb-4">📅</div>
    <h3 class="font-bold text-gray-700 dark:text-slate-300 text-lg mb-2">Fitur Reservasi Segera Hadir</h3>
    <p class="text-gray-400 dark:text-slate-500 text-sm max-w-md mx-auto">
        Laporan reservasi akan tersedia setelah tabel reservasi terhubung ke database.
        Saat ini sistem reservasi masih menggunakan form frontend tanpa penyimpanan ke DB.
    </p>
    <div class="mt-6 inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-xl text-sm font-medium">
        <span>💡</span> Data reservasi akan otomatis muncul setelah integrasi database selesai
    </div>
</div>
@endif

{{-- ===== TAB C: ABSENSI ===== --}}
@if($tab === 'absensi')

{{-- KPI Absensi --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
    @foreach([
        ['label'=>'Hadir',      'val'=>$absensiStats['hadir'],     'color'=>'from-emerald-500 to-teal-600',   'icon'=>'✅'],
        ['label'=>'Terlambat',  'val'=>$absensiStats['terlambat'], 'color'=>'from-yellow-500 to-amber-600',   'icon'=>'⏰'],
        ['label'=>'Izin',       'val'=>$absensiStats['izin'],      'color'=>'from-blue-500 to-cyan-600',      'icon'=>'📋'],
        ['label'=>'Sakit',      'val'=>$absensiStats['sakit'],     'color'=>'from-orange-500 to-red-500',     'icon'=>'🤒'],
        ['label'=>'Alpha',      'val'=>$absensiStats['alpha'],     'color'=>'from-red-500 to-rose-600',       'icon'=>'⛔'],
        ['label'=>'Rata Telat', 'val'=>$absensiStats['avg_late'].'m', 'color'=>'from-violet-500 to-purple-600','icon'=>'⏱'],
    ] as $kpi)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-9 h-9 bg-gradient-to-br {{ $kpi['color'] }} rounded-xl flex items-center justify-center text-base shadow mb-2">{{ $kpi['icon'] }}</div>
        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $kpi['val'] }}</div>
        <div class="text-xs text-gray-500 dark:text-slate-400">{{ $kpi['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Trend 7 Hari --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-5">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-gray-900 dark:text-white">Trend Kehadiran 7 Hari Terakhir</h3>
    </div>
    <div class="p-6">
        @php $maxTrend = max(array_map(fn($d) => $d['hadir'] + $d['terlambat'] + $d['alpha'], $absensiTrend) ?: [1]); @endphp
        <div class="flex items-end gap-3 h-36 mb-3">
            @foreach($absensiTrend as $d)
            @php
            $total = $d['hadir'] + $d['terlambat'] + $d['alpha'];
            $pctH  = $maxTrend > 0 ? ($d['hadir'] / $maxTrend) * 100 : 0;
            $pctT  = $maxTrend > 0 ? ($d['terlambat'] / $maxTrend) * 100 : 0;
            $pctA  = $maxTrend > 0 ? ($d['alpha'] / $maxTrend) * 100 : 0;
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full flex flex-col justify-end rounded-t-lg overflow-hidden" style="height:120px">
                    <div class="w-full bg-red-400 transition-all" style="height:{{ max($pctA*1.2,0) }}px" title="Alpha: {{ $d['alpha'] }}"></div>
                    <div class="w-full bg-yellow-400 transition-all" style="height:{{ max($pctT*1.2,0) }}px" title="Terlambat: {{ $d['terlambat'] }}"></div>
                    <div class="w-full bg-emerald-500 transition-all" style="height:{{ max($pctH*1.2,0) }}px" title="Hadir: {{ $d['hadir'] }}"></div>
                </div>
                <div class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ $d['short'] }}</div>
                <div class="text-[10px] text-gray-400">{{ $d['date'] }}</div>
            </div>
            @endforeach
        </div>
        <div class="flex items-center gap-4 pt-3 border-t border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-xs text-gray-500">Hadir</span></div>
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-yellow-400"></div><span class="text-xs text-gray-500">Terlambat</span></div>
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-red-400"></div><span class="text-xs text-gray-500">Alpha</span></div>
        </div>
    </div>
</div>

{{-- Rekap Per Karyawan --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-gray-900 dark:text-white">Rekap Per Karyawan</h3>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ Carbon::create($year,$month,1)->locale('id')->isoFormat('MMMM YYYY') }}</p>
    </div>
    @if($absensiPerKaryawan->isEmpty())
    <div class="py-10 text-center text-gray-400 text-sm">Belum ada karyawan aktif</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-slate-700/50">
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3 uppercase">Karyawan</th>
                <th class="text-center text-xs font-semibold text-gray-500 dark:text-slate-400 px-3 py-3 uppercase">Hadir</th>
                <th class="text-center text-xs font-semibold text-gray-500 dark:text-slate-400 px-3 py-3 uppercase">Terlambat</th>
                <th class="text-center text-xs font-semibold text-gray-500 dark:text-slate-400 px-3 py-3 uppercase">Izin</th>
                <th class="text-center text-xs font-semibold text-gray-500 dark:text-slate-400 px-3 py-3 uppercase">Sakit</th>
                <th class="text-center text-xs font-semibold text-gray-500 dark:text-slate-400 px-3 py-3 uppercase">Alpha</th>
                <th class="text-center text-xs font-semibold text-gray-500 dark:text-slate-400 px-3 py-3 uppercase">Rata Telat</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($absensiPerKaryawan as $row)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $row['employee']->user->avatar_url }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $row['employee']->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $row['employee']->jabatan }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-3.5 text-center"><span class="text-sm font-bold text-emerald-600">{{ $row['hadir'] }}</span></td>
                    <td class="px-3 py-3.5 text-center"><span class="text-sm font-bold {{ $row['terlambat'] > 0 ? 'text-yellow-600' : 'text-gray-300 dark:text-slate-600' }}">{{ $row['terlambat'] }}</span></td>
                    <td class="px-3 py-3.5 text-center"><span class="text-sm font-bold {{ $row['izin'] > 0 ? 'text-blue-600' : 'text-gray-300 dark:text-slate-600' }}">{{ $row['izin'] }}</span></td>
                    <td class="px-3 py-3.5 text-center"><span class="text-sm font-bold {{ $row['sakit'] > 0 ? 'text-orange-600' : 'text-gray-300 dark:text-slate-600' }}">{{ $row['sakit'] }}</span></td>
                    <td class="px-3 py-3.5 text-center"><span class="text-sm font-bold {{ $row['alpha'] > 0 ? 'text-red-600' : 'text-gray-300 dark:text-slate-600' }}">{{ $row['alpha'] }}</span></td>
                    <td class="px-3 py-3.5 text-center"><span class="text-xs {{ $row['avg_late'] > 0 ? 'text-yellow-600 font-bold' : 'text-gray-400' }}">{{ $row['avg_late'] > 0 ? $row['avg_late'].' mnt' : '—' }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif

{{-- ===== TAB D: CUTI ===== --}}
@if($tab === 'cuti')

{{-- KPI Cuti --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-5">
    @foreach([
        ['label'=>'Total Pengajuan', 'val'=>$cutiStats['total'],     'icon'=>'📋', 'color'=>'from-violet-500 to-purple-600'],
        ['label'=>'Disetujui',       'val'=>$cutiStats['disetujui'], 'icon'=>'✅', 'color'=>'from-emerald-500 to-teal-600'],
        ['label'=>'Menunggu',        'val'=>$cutiStats['menunggu'],  'icon'=>'⏳', 'color'=>'from-yellow-500 to-amber-600'],
        ['label'=>'Ditolak',         'val'=>$cutiStats['ditolak'],   'icon'=>'❌', 'color'=>'from-red-500 to-rose-600'],
        ['label'=>'Total Hari Cuti', 'val'=>$cutiStats['total_hari'].' hari', 'icon'=>'📅', 'color'=>'from-blue-500 to-cyan-600'],
    ] as $k)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="w-11 h-11 bg-gradient-to-br {{ $k['color'] }} rounded-xl flex items-center justify-center text-xl shadow-lg mb-3">{{ $k['icon'] }}</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $k['val'] }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">{{ $k['label'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Distribusi Jenis Cuti --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-gray-900 dark:text-white mb-5">Distribusi Jenis Cuti</h3>
        @php
        $cutiTypes = [
            'cuti_tahunan' => ['label'=>'Cuti Tahunan', 'icon'=>'🏖️', 'color'=>'bg-violet-500'],
            'sakit'        => ['label'=>'Sakit',        'icon'=>'🤒', 'color'=>'bg-orange-500'],
            'izin'         => ['label'=>'Izin',         'icon'=>'📋', 'color'=>'bg-blue-500'],
            'cuti_khusus'  => ['label'=>'Cuti Khusus',  'icon'=>'⭐', 'color'=>'bg-emerald-500'],
        ];
        $totalCuti = max(array_sum($cutiByType), 1);
        @endphp
        <div class="space-y-4">
            @foreach($cutiTypes as $key => $ct)
            @php $cnt = $cutiByType[$key] ?? 0; $pct = round(($cnt / $totalCuti) * 100); @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm text-gray-600 dark:text-slate-400 flex items-center gap-2">
                        <span>{{ $ct['icon'] }}</span> {{ $ct['label'] }}
                    </span>
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">{{ $cnt }} <span class="text-gray-400 font-normal text-xs">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full {{ $ct['color'] }} rounded-full transition-all duration-700" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Status Cuti --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-gray-900 dark:text-white mb-5">Status Pengajuan</h3>
        @php
        $statusCuti = [
            'disetujui' => ['label'=>'Disetujui', 'color'=>'bg-emerald-500', 'badge'=>'bg-emerald-100 text-emerald-700'],
            'menunggu'  => ['label'=>'Menunggu',  'color'=>'bg-yellow-500',  'badge'=>'bg-yellow-100 text-yellow-700'],
            'ditolak'   => ['label'=>'Ditolak',   'color'=>'bg-red-500',     'badge'=>'bg-red-100 text-red-700'],
        ];
        $totalStatus = max($cutiStats['total'], 1);
        @endphp
        <div class="space-y-4">
            @foreach($statusCuti as $key => $sc)
            @php $cnt = $cutiStats[$key] ?? 0; $pct = round(($cnt / $totalStatus) * 100); @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-semibold px-2.5 py-1 rounded-full {{ $sc['badge'] }}">{{ $sc['label'] }}</span>
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">{{ $cnt }} <span class="text-gray-400 font-normal text-xs">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full {{ $sc['color'] }} rounded-full transition-all duration-700" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Daftar Pengajuan Cuti --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-900 dark:text-white">Daftar Pengajuan Cuti</h3>
            <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ Carbon::create($year,$month,1)->locale('id')->isoFormat('MMMM YYYY') }}</p>
        </div>
        <span class="text-xs bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 px-2.5 py-1 rounded-full">{{ $leaves->count() }} pengajuan</span>
    </div>
    @if($leaves->isEmpty())
    <div class="py-12 text-center">
        <div class="text-4xl mb-3">🏖️</div>
        <p class="text-gray-400 dark:text-slate-500 text-sm">Tidak ada pengajuan cuti bulan ini</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="bg-gray-50 dark:bg-slate-700/50">
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3 uppercase">Karyawan</th>
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Jenis</th>
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Periode</th>
                <th class="text-center text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Hari</th>
                <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3 uppercase">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($leaves as $leave)
                @php $sb = $leave->status_badge; @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $leave->employee->user->avatar_url }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $leave->employee->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $leave->employee->jabatan }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-sm text-gray-600 dark:text-slate-400">{{ $leave->type_label }}</td>
                    <td class="px-4 py-3.5 text-sm text-gray-600 dark:text-slate-400">
                        {{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="text-sm font-bold text-gray-700 dark:text-slate-300">{{ $leave->total_days }}</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sb['class'] }}">{{ $sb['label'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif

@endsection
