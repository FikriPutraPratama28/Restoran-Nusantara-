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
    ] as $t)
    <a href="{{ request()->fullUrlWithQuery(['tab' => $t['key']]) }}"
        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-semibold whitespace-nowrap transition-all
        {{ $tab === $t['key'] ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/30' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
        <span>{{ $t['icon'] }}</span>
        <span>{{ $t['label'] }}</span>
        @if($t['key'] === 'penjualan')
            @php
            $spark = array_column($salesHarian ?? [], 'items') ?: [];
            $smax = max($spark ?: [1]);
            @endphp
            <span class="ml-3 hidden sm:inline-block">
                <svg width="72" height="18" viewBox="0 0 72 18" class="opacity-90">
                    @foreach($spark as $i => $v)
                        @php $w = 6; $gap = 3; $x = $i * ($w + $gap); $h = (int) max(($v / max($smax,1)) * 14, 2); $y = 18 - $h; @endphp
                        <rect x="{{ $x }}" y="{{ $y }}" width="{{ $w }}" height="{{ $h }}" rx="2" fill="url(#sp{{ $i }})"></rect>
                        <defs>
                            <linearGradient id="sp{{ $i }}" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#7c3aed" />
                                <stop offset="100%" stop-color="#a78bfa" />
                            </linearGradient>
                        </defs>
                    @endforeach
                </svg>
            </span>
        @endif
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

{{-- Grafik Penjualan --}}
@php
    $salesData = $range === 'mingguan' ? $salesMingguan : ($range === 'bulanan' ? $salesBulanan : $salesHarian);
    $maxChart = max(array_column($salesData ?? [['items'=>1]], 'items')) ?: 1;
@endphp
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-5 p-4">
    <div class="px-2 py-2 border-b border-gray-100 dark:border-slate-700 mb-4">
        <h3 class="font-bold text-gray-900 dark:text-white">Grafik Penjualan</h3>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">7 hari terakhir / rentang: {{ ucfirst($range) }}</p>
    </div>

    {{-- KPI-based Chart (uses the four KPI values) --}}
    @php
        $kpiChart = [
            ['label' => 'Total Item Terjual', 'value' => $totalItems, 'meta' => 'items'],
            ['label' => 'Est. Pendapatan', 'value' => $totalRevEst, 'meta' => 'currency'],
            ['label' => 'Rating Rata-rata', 'value' => $avgRating, 'meta' => 'rating'],
            ['label' => 'Menu Stok Habis', 'value' => $menuHabis, 'meta' => 'count'],
        ];
        $kpiMax = max(array_map(fn($x) => is_numeric($x['value']) ? $x['value'] : 0, $kpiChart));
        if($kpiMax <= 0) $kpiMax = 1;
    @endphp
    <div x-data="{ kpis: @json($kpiChart), maxVal: {{ $kpiMax }}, tooltip: null }" class="mb-3">
        <div class="flex items-end gap-4 h-44">
            <template x-for="(k, i) in kpis" :key="i">
                <div class="w-1/4 flex flex-col items-center gap-2 relative">
                    <div x-show="tooltip === i" x-cloak class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap z-10 pointer-events-none">
                        <span x-text="(k.meta === 'currency') ? 'Rp ' + new Intl.NumberFormat('id-ID').format(k.value) : (k.meta === 'rating' ? parseFloat(k.value).toFixed(1) : k.value)"></span>
                    </div>
                    <div class="w-full flex justify-center items-end" style="height: 160px;">
                        <div @mouseenter="tooltip = i" @mouseleave="tooltip = null" class="w-11/12 rounded-t-lg bg-gradient-to-t from-violet-600 to-violet-400 cursor-pointer transition-all duration-300" :style="`height: ${Math.max((k.value / maxVal) * 160, 6)}px`"></div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-semibold text-gray-800 dark:text-white" x-text="k.label"></div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1" x-text="(k.meta === 'currency') ? 'Rp ' + new Intl.NumberFormat('id-ID').format(k.value) : (k.meta === 'rating' ? parseFloat(k.value).toFixed(1) : k.value)"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="flex items-center gap-4 mt-2 pt-2 border-t border-gray-100 dark:border-slate-700">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-violet-500"></div>
            <span class="text-xs text-gray-500 dark:text-slate-400">Estimasi penjualan</span>
        </div>
        <div class="ml-auto text-sm font-bold text-gray-900 dark:text-white">Total: <span class="text-violet-600">Rp {{ number_format(array_sum(array_column($salesData ?? [], 'rev')),0,',','.') }}</span></div>
    </div>
</div>

@endif

{{-- Absensi section removed per request --}}

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
