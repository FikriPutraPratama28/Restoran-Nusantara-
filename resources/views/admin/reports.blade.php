@extends('admin.layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan Lengkap')
@section('page-subtitle', 'Analitik riil penjualan dan reservasi restoran')

@section('content')
@php use Carbon\Carbon; @endphp

{{-- ===== FILTER BAR ===== --}}
<div class="bg-white dark:bg-slate-800 rounded-3xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 mb-6">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <label class="text-xs font-bold tracking-wider text-slate-400 dark:text-slate-550 uppercase">Periode:</label>
        <select name="month" class="px-3 py-2 rounded-2xl border border-gray-200 dark:border-slate-655 bg-gray-50 dark:bg-slate-700 text-xs font-semibold text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ Carbon::create(null,$m)->locale('id')->isoFormat('MMMM') }}</option>
            @endforeach
        </select>
        <select name="year" class="px-3 py-2 rounded-2xl border border-gray-200 dark:border-slate-655 bg-gray-50 dark:bg-slate-700 text-xs font-semibold text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            @foreach(range(date('Y')-2, date('Y')) as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-2xl transition-all shadow-sm">Filter</button>
        <span class="text-gray-200 dark:text-slate-750">|</span>
        <span class="text-xs text-slate-400 dark:text-slate-500">Laporan aktif: <strong class="text-slate-600 dark:text-slate-350">{{ Carbon::create($year,$month,1)->locale('id')->isoFormat('MMMM YYYY') }}</strong></span>
    </form>
</div>

{{-- ===== TAB NAVIGATION (BENTO STYLE) ===== --}}
<div class="flex gap-2 bg-white dark:bg-slate-800 p-1.5 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 mb-6 overflow-x-auto">
    <a href="{{ request()->fullUrlWithQuery(['tab' => 'penjualan']) }}"
        class="flex-1 flex items-center justify-center gap-2 py-3 px-5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all
        {{ $tab === 'penjualan' ? 'bg-violet-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <span>Laporan Penjualan</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['tab' => 'reservasi']) }}"
        class="flex-1 flex items-center justify-center gap-2 py-3 px-5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all
        {{ $tab === 'reservasi' ? 'bg-violet-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>Laporan Reservasi</span>
    </a>
</div>

{{-- ===== TAB A: PENJUALAN ===== --}}
@if($tab === 'penjualan')

{{-- Bento KPI Penjualan --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $kpiSales = [
        [
            'label' => 'Total Item Terjual',
            'value' => number_format($totalItems),
            'sub'   => 'Unit makanan/minuman',
            'color' => 'from-violet-500 to-purple-600',
            'svg'   => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>'
        ],
        [
            'label' => 'Total Pendapatan',
            'value' => 'Rp ' . number_format($totalRevEst, 0, ',', '.'),
            'sub'   => 'Berdasarkan menu dipesan',
            'color' => 'from-emerald-500 to-teal-600',
            'svg'   => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0"/></svg>'
        ],
        [
            'label' => 'Rating Menu',
            'value' => number_format($avgRating, 1) . ' / 5.0',
            'sub'   => 'Rata-rata rating menu',
            'color' => 'from-yellow-500 to-amber-600',
            'svg'   => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>'
        ],
        [
            'label' => 'Stok Menu Habis',
            'value' => number_format($menuHabis),
            'sub'   => 'Perlu restock segera',
            'color' => 'from-red-500 to-rose-600',
            'svg'   => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
        ],
    ];
    @endphp
    @foreach($kpiSales as $k)
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between">
        <div class="flex items-start justify-between">
            <div class="w-10 h-10 rounded-2xl bg-slate-500/10 text-violet-600 dark:text-violet-400 flex items-center justify-center">{!! $k['svg'] !!}</div>
        </div>
        <div class="mt-4">
            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $k['value'] }}</div>
            <div class="text-xs font-semibold text-gray-500 dark:text-slate-400 mt-1">{{ $k['label'] }}</div>
            <div class="text-[10px] text-gray-400 dark:text-slate-500 mt-0.5">{{ $k['sub'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Grafik Penjualan (Bento Box) --}}
@php
    $salesData = $range === 'mingguan' ? $salesMingguan : ($range === 'bulanan' ? $salesBulanan : $salesHarian);
@endphp
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 mb-6 p-6">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 dark:border-slate-700/50 pb-4 mb-4">
        <div>
            <h3 class="font-bold text-gray-950 dark:text-white">Trend Grafik Penjualan</h3>
            <p class="text-xs text-slate-400 mt-0.5">Analisis histori berdasarkan range terpilih</p>
        </div>
        <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-slate-700/50 p-1 rounded-2xl border border-gray-150/10">
            @foreach(['harian' => 'Hari', 'mingguan' => 'Minggu', 'bulanan' => 'Bulan'] as $k => $lbl)
            <a href="{{ request()->fullUrlWithQuery(['range' => $k]) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all
               {{ $range === $k ? 'bg-white dark:bg-slate-800 text-violet-600 dark:text-white shadow-sm' : 'text-slate-400 hover:text-slate-650' }}">
               {{ $lbl }}
            </a>
            @endforeach
        </div>
    </div>

    <div class="relative h-72 w-full">
        <canvas id="salesChartReports"></canvas>
    </div>
</div>

{{-- Top Menu Terlaris (Bento Box) --}}
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
    <h3 class="font-bold text-gray-950 dark:text-white mb-1">Daftar Menu Terlaris</h3>
    <p class="text-xs text-slate-400 mb-5">Diurutkan berdasarkan total porsi terjual</p>
    
    @if($topMenus->isEmpty())
    <div class="text-center py-10 text-gray-400">
        <p class="text-sm">Belum ada data porsi terjual</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($topMenus as $i => $menu)
        @php $pct = $topMenus->max('sold_count') > 0 ? ($menu->sold_count / $topMenus->max('sold_count')) * 100 : 0; @endphp
        <div class="flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-slate-700/20 rounded-2xl border border-gray-100/10">
            <span class="text-xs font-bold text-slate-400 w-5 text-center">{{ $i + 1 }}</span>
            <img src="{{ $menu->image_src }}" alt="{{ $menu->name }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-gray-100/10">
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-0.5">
                    <span class="text-xs font-semibold text-gray-800 dark:text-slate-200 truncate">{{ $menu->name }}</span>
                    <span class="text-xs font-bold text-gray-900 dark:text-white">{{ number_format($menu->sold_count) }} porsi</span>
                </div>
                <div class="w-full h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-violet-500 to-indigo-500 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxSales = document.getElementById('salesChartReports').getContext('2d');
    const salesData = @json($salesData);
    
    const labels = salesData.map(d => d.label || d.short);
    const qtyData = salesData.map(d => d.items);
    const revData = salesData.map(d => d.rev);
    
    const chartSales = new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pendapatan (Rupiah)',
                    data: revData,
                    backgroundColor: '#7c3aed',
                    borderRadius: 8,
                    yAxisID: 'yRev',
                    maxBarThickness: 40
                },
                {
                    label: 'Porsi Terjual (Qty)',
                    data: qtyData,
                    type: 'line',
                    borderColor: '#06b6d4',
                    borderWidth: 2,
                    tension: 0.3,
                    yAxisID: 'yQty',
                    pointBackgroundColor: '#06b6d4',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#475569',
                        font: { weight: '600' }
                    }
                },
                tooltip: {
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.datasetIndex === 0) {
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            } else {
                                label += context.raw + ' pcs';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#64748b' : '#94a3b8' }
                },
                yRev: {
                    type: 'linear',
                    position: 'left',
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? 'rgba(51, 65, 85, 0.5)' : 'rgba(226, 232, 240, 0.8)'
                    },
                    ticks: {
                        color: '#7c3aed',
                        font: { weight: '600' },
                        callback: function(val) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(val);
                        }
                    }
                },
                yQty: {
                    type: 'linear',
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        color: '#06b6d4',
                        font: { weight: '600' },
                        callback: function(val) { return val + ' pcs'; }
                    }
                }
            }
        }
    });
    
    const observer = new MutationObserver(() => {
        const isDark = document.documentElement.classList.contains('dark');
        chartSales.options.plugins.legend.labels.color = isDark ? '#94a3b8' : '#475569';
        chartSales.options.scales.yRev.grid.color = isDark ? 'rgba(51, 65, 85, 0.5)' : 'rgba(226, 232, 240, 0.8)';
        chartSales.update();
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});
</script>

@endif

{{-- ===== TAB B: RESERVASI ===== --}}
@if($tab === 'reservasi')

{{-- Bento KPI Reservasi --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    @php
    $kpiReservasi = [
        ['label'=>'Total Pendaftaran', 'val'=>$reservasiStats['total'],      'color'=>'text-violet-650', 'bg'=>'bg-violet-50 dark:bg-violet-900/20', 'icon'=>'<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'],
        ['label'=>'Terkonfirmasi',     'val'=>$reservasiStats['konfirmasi'], 'color'=>'text-blue-600',   'bg'=>'bg-blue-50 dark:bg-blue-900/20',     'icon'=>'<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/></svg>'],
        ['label'=>'Menunggu',          'val'=>$reservasiStats['menunggu'],   'color'=>'text-yellow-600', 'bg'=>'bg-yellow-50 dark:bg-yellow-900/20', 'icon'=>'<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0"/></svg>'],
        ['label'=>'Selesai',           'val'=>$reservasiStats['selesai'],    'color'=>'text-emerald-600','bg'=>'bg-emerald-50 dark:bg-emerald-900/20', 'icon'=>'<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'],
        ['label'=>'Dibatalkan',        'val'=>$reservasiStats['batal'],      'color'=>'text-red-600',    'bg'=>'bg-red-50 dark:bg-red-900/20',       'icon'=>'<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'],
    ];
    @endphp
    @foreach($kpiReservasi as $k)
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between">
        <div class="w-9 h-9 rounded-xl {{ $k['bg'] }} {{ $k['color'] }} flex items-center justify-center flex-shrink-0">{!! $k['icon'] !!}</div>
        <div class="mt-4">
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $k['val'] }}</div>
            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-1 uppercase">{{ $k['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Grafik Reservasi --}}
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 mb-6 p-6">
    <h3 class="font-bold text-gray-950 dark:text-white">Aktivitas Reservasi 7 Hari Terakhir</h3>
    <p class="text-xs text-slate-400 mt-0.5 mb-4">Visualisasi tren pendaftaran reservasi masuk harian</p>
    <div class="relative h-64 w-full">
        <canvas id="reservationsChartReports"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxRes = document.getElementById('reservationsChartReports').getContext('2d');
    const resData = @json($reservasiHarian);
    
    const labels = resData.map(d => d.short + ' (' + d.date + ')');
    const countData = resData.map(d => d.total);
    
    const chartRes = new Chart(ctxRes, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Reservasi',
                data: countData,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#6366f1',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' reservasi';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#64748b' : '#94a3b8' }
                },
                y: {
                    type: 'linear',
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? 'rgba(51, 65, 85, 0.5)' : 'rgba(226, 232, 240, 0.8)'
                    },
                    ticks: {
                        color: '#6366f1',
                        font: { weight: '600' },
                        stepSize: 1,
                        callback: function(val) { return val + ' bkg'; }
                    }
                }
            }
        }
    });
    
    const observer = new MutationObserver(() => {
        const isDark = document.documentElement.classList.contains('dark');
        chartRes.options.scales.y.grid.color = isDark ? 'rgba(51, 65, 85, 0.5)' : 'rgba(226, 232, 240, 0.8)';
        chartRes.update();
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});
</script>

@endif

@endsection
