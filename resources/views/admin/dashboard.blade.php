@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan performa restoran hari ini')

@section('content')

{{-- ===== NAVIGASI CEPAT KATEGORI ===== --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @php
    $menuNav = [
        [
            'label'   => 'Manajemen',
            'icon'    => '⚙️',
            'color'   => 'from-violet-500 to-purple-600',
            'items'   => ['Menu Makanan', 'Reservasi', 'Jadwal Kerja'],
            'href'    => route('admin.menu'),
        ],
        // HR System removed
        [
            'label'   => 'Laporan',
            'icon'    => '📊',
            'color'   => 'from-emerald-500 to-teal-600',
            'items'   => ['Penjualan', 'Reservasi'],
            'href'    => route('admin.reports', ['tab' => 'penjualan']),
        ],
        [
            'label'   => 'Cerita Kami',
            'icon'    => '📖',
            'color'   => 'from-orange-500 to-amber-600',
            'items'   => ['Hero/Banner', 'Promo', 'Cerita Kami'],
            'href'    => route('admin.content.about'),
        ],
        [
            'label'   => 'Tim Kami',
            'icon'    => '👥',
            'color'   => 'from-blue-500 to-cyan-600',
            'items'   => ['Tambah/Edit/Hapus Anggota'],
            'href'    => route('admin.content.team'),
        ],
        [
            'label'   => 'Momen Bersama',
            'icon'    => '📸',
            'color'   => 'from-pink-500 to-pink-400',
            'items'   => ['Galeri Foto', 'Tambah/Edit/Hapus'],
            'href'    => route('admin.content.gallery'),
        ],
        [
            'label'   => 'Fasilitas Lengkap',
            'icon'    => '🏷️',
            'color'   => 'from-emerald-500 to-teal-600',
            'items'   => ['Daftar Fasilitas', 'Tambah/Edit/Hapus'],
            'href'    => route('admin.content.facility'),
        ],
    ];
    @endphp
    @foreach($menuNav as $nav)
    <a href="{{ $nav['href'] }}"
        class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-gradient-to-br {{ $nav['color'] }} rounded-xl flex items-center justify-center text-lg shadow flex-shrink-0">{{ $nav['icon'] }}</div>
            <span class="font-bold text-gray-800 dark:text-slate-200 text-sm">{{ $nav['label'] }}</span>
        </div>
        {{-- mini chart removed as requested --}}
        <div class="flex flex-wrap gap-1">
            @foreach($nav['items'] as $sub)
            <span class="text-[10px] bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 px-2 py-0.5 rounded-full">{{ $sub }}</span>
            @endforeach
        </div>
    </a>
    @endforeach
</div>

{{-- ===== KPI CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $kpis = [
        [
            'label'  => 'Total Pelanggan',
            'value'  => number_format($stats['total_pelanggan']),
            'sub'    => '+' . $stats['pelanggan_bulan_ini'] . ' bulan ini',
            'up'     => true,
            'icon'   => '👥',
            'color'  => 'from-violet-500 to-purple-600',
            'href'   => route('admin.customers'),
        ],
        [
            'label'  => 'Total Transaksi',
            'value'  => number_format($stats['total_transaksi']),
            'sub'    => 'item terjual',
            'up'     => true,
            'icon'   => '🛒',
            'color'  => 'from-emerald-500 to-teal-600',
            'href'   => route('admin.orders'),
        ],
        [
            'label'  => 'Menu Aktif',
            'value'  => number_format($stats['total_menu_aktif']),
            'sub'    => $stats['menu_habis'] . ' stok habis',
            'up'     => $stats['menu_habis'] === 0,
            'icon'   => '🍽️',
            'color'  => 'from-orange-500 to-amber-600',
            'href'   => route('admin.menu'),
        ],
    ];
    @endphp
    @foreach($kpis as $kpi)
    <a href="{{ $kpi['href'] }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:-translate-y-0.5 transition-all block">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 bg-gradient-to-br {{ $kpi['color'] }} rounded-xl flex items-center justify-center text-xl shadow-lg">{{ $kpi['icon'] }}</div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $kpi['up'] ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400' }}">
                {{ $kpi['sub'] }}
            </span>
        </div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $kpi['value'] }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400">{{ $kpi['label'] }}</div>
    </a>
    @endforeach
</div>

{{-- ===== STATS ROW 2 ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $stats2 = [
        ['label'=>'Promo Aktif',      'value'=>$stats['promo_aktif'],        'icon'=>'🎁', 'color'=>'text-pink-600',    'bg'=>'bg-pink-50 dark:bg-pink-900/20',      'href'=>route('admin.content.promo')],
    ];
    @endphp
    @foreach($stats2 as $s)
    <a href="{{ $s['href'] }}" class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-all flex items-center gap-4">
        <div class="w-12 h-12 {{ $s['bg'] }} rounded-xl flex items-center justify-center text-2xl flex-shrink-0">{{ $s['icon'] }}</div>
        <div>
            <div class="text-2xl font-bold {{ $s['color'] }}">{{ $s['value'] }}</div>
            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $s['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- ===== GRAFIK PENJUALAN + DISTRIBUSI MENU ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Grafik Penjualan 7 Hari --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Grafik Penjualan</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400">7 hari terakhir (estimasi dari sold_count)</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-violet-600">{{ number_format($stats['total_transaksi']) }}</div>
                <div class="text-xs text-gray-400">total item terjual</div>
            </div>
        </div>
        {{-- Bar Chart dari data real --}}
        <div class="flex items-end gap-2 h-44"
            x-data='{
                bars: @json($chartData),
                maxVal: {{ $maxChart }},
                tooltip: null
            }'>
            <template x-for="(b, i) in bars" :key="i">
                <div class="flex-1 flex flex-col items-center gap-2 group relative">
                    {{-- Tooltip --}}
                    <div x-show="tooltip === i" x-cloak
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap z-10 pointer-events-none">
                        <span x-text="b.rev"></span>
                    </div>
                    {{-- Bar --}}
                    <div class="w-full flex justify-center items-end" style="height: 160px;">
                        <div
                            @mouseenter="tooltip = i"
                            @mouseleave="tooltip = null"
                            class="w-full rounded-t-lg bg-gradient-to-t from-violet-600 to-violet-400 hover:from-violet-700 hover:to-violet-500 cursor-pointer transition-all duration-300"
                            :style="`height: ${Math.max((b.value / maxVal) * 160, 4)}px`">
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-500 dark:text-slate-400 font-medium" x-text="b.day"></div>
                        <div class="text-[10px] text-gray-400" x-text="b.date"></div>
                    </div>
                </div>
            </template>
        </div>
        <div class="flex items-center gap-4 mt-2 pt-4 border-t border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-violet-500"></div>
                <span class="text-xs text-gray-500 dark:text-slate-400">Estimasi penjualan harian</span>
            </div>
            <div class="ml-auto text-sm font-bold text-gray-900 dark:text-white">
                Est. Pendapatan: <span class="text-violet-600">Rp {{ number_format($stats['total_transaksi'] * 35000, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Distribusi Menu per Kategori --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Distribusi Menu</h3>
        <p class="text-sm text-gray-500 dark:text-slate-400 mb-5">Per kategori (aktif)</p>
        @php
        $categoryColors = [
            'makanan' => ['bar'=>'bg-violet-500',  'badge'=>'bg-violet-100 text-violet-700',  'icon'=>'🍽️'],
            'minuman' => ['bar'=>'bg-cyan-500',     'badge'=>'bg-cyan-100 text-cyan-700',      'icon'=>'🥤'],
            'dessert' => ['bar'=>'bg-pink-500',     'badge'=>'bg-pink-100 text-pink-700',      'icon'=>'🍰'],
            'snack'   => ['bar'=>'bg-amber-500',    'badge'=>'bg-amber-100 text-amber-700',    'icon'=>'🍟'],
            'paket'   => ['bar'=>'bg-emerald-500',  'badge'=>'bg-emerald-100 text-emerald-700','icon'=>'📦'],
        ];
        $totalMenuCat = array_sum($menuByCategory) ?: 1;
        @endphp
        <div class="space-y-3">
            @foreach($categoryColors as $cat => $style)
            @php $count = $menuByCategory[$cat] ?? 0; $pct = round(($count / $totalMenuCat) * 100); @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-600 dark:text-slate-400 flex items-center gap-1.5">
                        <span>{{ $style['icon'] }}</span>
                        <span class="capitalize">{{ $cat }}</span>
                    </span>
                    <span class="text-xs font-bold text-gray-700 dark:text-slate-300">{{ $count }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full {{ $style['bar'] }} rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <span class="text-sm text-gray-500 dark:text-slate-400">Total menu aktif</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_menu_aktif'] }}</span>
        </div>
        @if($stats['menu_habis'] > 0)
        <a href="{{ route('admin.menu') }}" class="mt-2 flex items-center gap-2 text-xs text-orange-600 dark:text-orange-400 hover:underline">
            <span>⚠️</span> {{ $stats['menu_habis'] }} menu stok habis — kelola sekarang
        </a>
        @endif
    </div>
</div>

{{-- ===== TOP MENU + ABSENSI HARI INI ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Top Menu (real dari sold_count) --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Menu Terlaris</h3>
                <p class="text-xs text-gray-500 dark:text-slate-400">Berdasarkan total terjual</p>
            </div>
            <a href="{{ route('admin.menu') }}" class="text-violet-600 text-xs font-medium hover:underline">Kelola →</a>
        </div>
        @if($topMenus->isEmpty())
        <div class="text-center py-10 text-gray-400 dark:text-slate-500">
            <div class="text-4xl mb-2">🍽️</div>
            <p class="text-sm">Belum ada data menu</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($topMenus as $i => $menu)
            @php $pct = $maxSold > 0 ? round(($menu->sold_count / $maxSold) * 100) : 0; @endphp
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-400 w-5 text-center flex-shrink-0">{{ $i + 1 }}</span>
                <img src="{{ $menu->image_src }}" alt="{{ $menu->name }}"
                    class="w-11 h-11 rounded-xl object-cover flex-shrink-0 border border-gray-100 dark:border-slate-600">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-slate-200 truncate">{{ $menu->name }}</p>
                        <span class="text-xs font-bold text-gray-700 dark:text-slate-300 ml-2 flex-shrink-0">{{ number_format($menu->sold_count) }} terjual</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-violet-500 to-purple-400 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0 capitalize">{{ $menu->category }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Absensi removed per request --}}
</div>

{{-- Karyawan Terbaru section removed per request --}}

{{-- ===== AKSI CEPAT ===== --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach([
            ['href'=>route('admin.menu'),               'icon'=>'🍽️', 'label'=>'Menu Makanan',       'color'=>'bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/30 text-orange-700 dark:text-orange-400'],
            // Absensi & Pengajuan Cuti actions removed
            ['href'=>route('admin.reservations'),       'icon'=>'📅', 'label'=>'Reservasi',          'color'=>'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 text-blue-700 dark:text-blue-400'],
            ['href'=>route('admin.content.promo'),      'icon'=>'🎁', 'label'=>'Kelola Promo',       'color'=>'bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/30 text-pink-700 dark:text-pink-400'],
            ['href'=>route('admin.customers'),          'icon'=>'👥', 'label'=>'Data Pelanggan',     'color'=>'bg-cyan-50 dark:bg-cyan-900/20 hover:bg-cyan-100 dark:hover:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400'],
            ['href'=>route('admin.reports', ['tab'=>'penjualan']), 'icon'=>'📊', 'label'=>'Laporan Penjualan', 'color'=>'bg-gray-50 dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300'],
        ] as $action)
        <a href="{{ $action['href'] }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ $action['color'] }} transition-all hover:scale-105 active:scale-95">
            <span class="text-xl">{{ $action['icon'] }}</span>
            <span>{{ $action['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>

@endsection
