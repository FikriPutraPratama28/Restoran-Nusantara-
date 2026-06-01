@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan performa restoran hari ini')

@section('content')

{{-- ===== ROW 1: Welcome Banner + System Status ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_220px] gap-4 mb-5">

    {{-- Welcome Banner --}}
    <div class="rounded-2xl p-6 relative overflow-hidden flex flex-col justify-between min-h-[160px]"
         style="background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 45%, #7c3aed 100%); border: 1px solid rgba(124,58,237,0.3);">
        {{-- Decorative circles --}}
        <div class="absolute right-0 top-0 w-52 h-52 rounded-full -translate-y-1/3 translate-x-1/3"
             style="background: rgba(255,255,255,0.04);"></div>
        <div class="absolute right-16 bottom-0 w-40 h-40 rounded-full translate-y-1/2"
             style="background: rgba(255,255,255,0.03);"></div>

        <div class="relative z-10">
            <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full"
                  style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.1);">
                Admin Portal
            </span>
            <h2 class="font-extrabold text-white mt-3 mb-1.5 font-jakarta leading-tight"
                style="font-size: 22px; letter-spacing: -0.02em;">
                Selamat Datang, {{ session('admin_name', 'Administrator') }}!
            </h2>
            <p class="text-[13px] max-w-xl" style="color: rgba(255,255,255,0.65);">
                Pantau seluruh operasional &mdash; menu, reservasi, promo, dan kinerja restoran secara real-time.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-5 mt-4 pt-4 text-[12px]"
             style="border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span style="color: rgba(255,255,255,0.7);">Sistem <strong class="text-white">Operasional</strong></span>
            </div>
            <span style="color: rgba(255,255,255,0.7);">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    {{-- System Status --}}
    <div class="bg-admin-card rounded-2xl p-5 flex flex-col justify-between" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Status Sistem</span>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(124,58,237,0.12);">
                <svg class="w-4 h-4" fill="none" stroke="#a78bfa" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
        <div>
            <div class="font-extrabold text-slate-100 font-jakarta" style="font-size: 28px;">v1.0.0</div>
            <div class="text-[11.5px] text-slate-500 mt-1">Laravel {{ app()->version() }} &middot; PHP {{ PHP_VERSION }}</div>
        </div>
        <div class="mt-4 pt-3.5 flex items-center justify-between text-[11.5px]"
             style="border-top: 1px solid rgba(255,255,255,0.06);">
            <span class="text-slate-500">Mode Debug</span>
            <span class="font-bold {{ config('app.debug') ? 'text-amber-400' : 'text-emerald-400' }}">
                {{ config('app.debug') ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>
</div>

{{-- ===== ROW 2: KPI Cards ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

    {{-- KPI 1: Total Omset --}}
    <div class="bg-admin-card rounded-2xl p-5 relative overflow-hidden" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(52,211,153,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(52,211,153,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#34d399" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(52,211,153,0.1); color: #34d399;">
                Total Omset
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 20px; letter-spacing: -0.02em;">
            Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Total Omset</div>
        <div class="text-[11px] text-slate-600 mt-0.5">Dari reservasi aktif</div>
    </div>

    {{-- KPI 2: Total Reservasi --}}
    <a href="{{ route('admin.reservations') }}"
       class="bg-admin-card rounded-2xl p-5 relative overflow-hidden block hover:opacity-90 transition-opacity"
       style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(139,92,246,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#a78bfa" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(139,92,246,0.1); color: #a78bfa;">
                Reservasi
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 20px; letter-spacing: -0.02em;">
            {{ number_format($stats['total_transaksi']) }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Total Reservasi</div>
        <div class="text-[11px] text-slate-600 mt-0.5">Pesanan terdaftar</div>
    </a>

    {{-- KPI 3: Menu Aktif --}}
    <a href="{{ route('admin.menu') }}"
       class="bg-admin-card rounded-2xl p-5 relative overflow-hidden block hover:opacity-90 transition-opacity"
       style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(251,191,36,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(251,191,36,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#fbbf24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(239,68,68,0.1); color: #f87171;">
                {{ $stats['menu_habis'] }} Habis
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 20px; letter-spacing: -0.02em;">
            {{ number_format($stats['total_menu_aktif']) }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Menu Aktif</div>
        <div class="text-[11px] text-slate-600 mt-0.5">Daftar menu terdaftar</div>
    </a>

    {{-- KPI 4: Total Pelanggan --}}
    <a href="{{ route('admin.customers') }}"
       class="bg-admin-card rounded-2xl p-5 relative overflow-hidden block hover:opacity-90 transition-opacity"
       style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="absolute top-0 right-0 w-20 h-20" style="background: rgba(56,189,248,0.06); border-radius: 0 16px 0 80px;"></div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(56,189,248,0.12);">
                <svg class="w-5 h-5" fill="none" stroke="#38bdf8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/>
                </svg>
            </div>
            <span class="text-[10.5px] font-bold px-2 py-1 rounded-full" style="background: rgba(56,189,248,0.1); color: #38bdf8;">
                +{{ $stats['pelanggan_bulan_ini'] }} Baru
            </span>
        </div>
        <div class="font-extrabold text-slate-100 font-jakarta leading-tight" style="font-size: 20px; letter-spacing: -0.02em;">
            {{ number_format($stats['total_pelanggan']) }}
        </div>
        <div class="text-[12px] text-slate-500 mt-1">Akun Pelanggan</div>
        <div class="text-[11px] text-slate-600 mt-0.5">Total terdaftar</div>
    </a>
</div>

{{-- ===== ROW 3: Sales Chart + Menu Distribution ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-4 mb-5">

    {{-- Sales Chart --}}
    <div class="bg-admin-card rounded-2xl p-5" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
            <div>
                <h3 class="font-bold text-slate-100 font-jakarta" style="font-size: 14px;">Performa Penjualan</h3>
                <p class="text-[12px] text-slate-500 mt-0.5">Analisis pendapatan &amp; item terjual (7 hari terakhir)</p>
            </div>
            <div class="text-right">
                <div class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Total Omset Grafik</div>
                <div class="font-extrabold font-jakarta mt-0.5" style="font-size: 18px; color: #a78bfa;">
                    Rp {{ number_format(array_sum(array_column($chartData, 'rev_num')), 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="relative" style="height: 220px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Menu Distribution --}}
    <div class="bg-admin-card rounded-2xl p-5 flex flex-col justify-between" style="border: 1px solid rgba(255,255,255,0.07);">
        <div>
            <h3 class="font-bold text-slate-100 font-jakarta mb-0.5" style="font-size: 14px;">Penyebaran Menu</h3>
            <p class="text-[12px] text-slate-500 mb-5">Distribusi per kategori aktif</p>

            @php
            $totalMenuCat = array_sum($menuByCategory) ?: 1;
            $catConfig = [
                'makanan' => [
                    'from' => '#7c3aed', 'to' => '#a78bfa',
                    'iconBg' => 'rgba(139,92,246,0.12)', 'iconColor' => '#a78bfa',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                ],
                'minuman' => [
                    'from' => '#0ea5e9', 'to' => '#38bdf8',
                    'iconBg' => 'rgba(56,189,248,0.12)', 'iconColor' => '#38bdf8',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6l1 7H8L9 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10c0 5 2 9 4 9s4-4 4-9"/>',
                ],
                'dessert' => [
                    'from' => '#ec4899', 'to' => '#f472b6',
                    'iconBg' => 'rgba(244,114,182,0.12)', 'iconColor' => '#f472b6',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                ],
                'snack' => [
                    'from' => '#d97706', 'to' => '#fbbf24',
                    'iconBg' => 'rgba(251,191,36,0.12)', 'iconColor' => '#fbbf24',
                    'icon' => '<rect stroke-linecap="round" stroke-linejoin="round" x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>',
                ],
                'paket' => [
                    'from' => '#10b981', 'to' => '#34d399',
                    'iconBg' => 'rgba(52,211,153,0.12)', 'iconColor' => '#34d399',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>',
                ],
            ];
            @endphp

            <div class="space-y-4">
                @foreach($catConfig as $cat => $cfg)
                @php
                    $count = $menuByCategory[$cat] ?? 0;
                    $pct = round(($count / $totalMenuCat) * 100);
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background: {{ $cfg['iconBg'] }};">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="{{ $cfg['iconColor'] }}" stroke-width="2.2" viewBox="0 0 24 24">
                                    {!! $cfg['icon'] !!}
                                </svg>
                            </div>
                            <span class="text-[12px] font-semibold text-slate-300 capitalize">{{ $cat }}</span>
                        </div>
                        <span class="text-[11.5px] font-bold text-slate-400">
                            {{ $count }} <span class="text-slate-600 font-normal">({{ $pct }}%)</span>
                        </span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.06);">
                        <div class="h-full rounded-full transition-all duration-700"
                             style="width: {{ $pct }}%; background: linear-gradient(90deg, {{ $cfg['from'] }}, {{ $cfg['to'] }});"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-5 pt-3.5 flex items-center justify-between text-[11.5px]"
             style="border-top: 1px solid rgba(255,255,255,0.06);">
            <span class="text-slate-500">Total menu terdaftar:</span>
            <span class="font-extrabold text-slate-100 font-jakarta">{{ $stats['total_menu_aktif'] }}</span>
        </div>
    </div>
</div>

{{-- ===== ROW 4: Top Menus + Recent Reservations ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-4 mb-5">

    {{-- Top Menus --}}
    <div class="bg-admin-card rounded-2xl p-5" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-slate-100 font-jakarta" style="font-size: 14px;">Menu Terlaris</h3>
                <p class="text-[11.5px] text-slate-500 mt-0.5">Berdasarkan total pesanan</p>
            </div>
            <a href="{{ route('admin.menu') }}"
               class="flex items-center gap-1 text-[11px] font-semibold text-violet-400 hover:text-violet-300 transition-colors">
                Kelola
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </div>

        @if($topMenus->isEmpty())
        <div class="py-10 text-center text-slate-600">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <p class="text-sm">Belum ada menu terjual</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($topMenus as $i => $menu)
            @php
                $pct = $maxSold > 0 ? round(($menu->sold_count / $maxSold) * 100) : 0;
                $rankColors = ['#f97316', '#94a3b8', '#94a3b8', '#94a3b8', '#94a3b8'];
                $rankBgs = ['rgba(249,115,22,0.1)', 'rgba(255,255,255,0.06)', 'rgba(255,255,255,0.06)', 'rgba(255,255,255,0.06)', 'rgba(255,255,255,0.06)'];
                $barGradients = [
                    'linear-gradient(90deg, #f97316, #fb923c)',
                    'linear-gradient(90deg, #7c3aed, #a78bfa)',
                    'linear-gradient(90deg, #0ea5e9, #38bdf8)',
                    'linear-gradient(90deg, #d97706, #fbbf24)',
                    'linear-gradient(90deg, #10b981, #34d399)',
                ];
            @endphp
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-bold w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0"
                      style="color: {{ $rankColors[$i] ?? '#94a3b8' }}; background: {{ $rankBgs[$i] ?? 'rgba(255,255,255,0.06)' }};">
                    #{{ $i + 1 }}
                </span>
                <img src="{{ $menu->image_src }}" alt="{{ $menu->name }}"
                     class="w-9 h-9 rounded-xl object-cover flex-shrink-0"
                     style="border: 1px solid rgba(255,255,255,0.08);">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-[12px] font-semibold text-slate-200 truncate max-w-[100px]">{{ $menu->name }}</p>
                        <span class="text-[11px] font-bold text-slate-400 flex-shrink-0 ml-1">{{ number_format($menu->sold_count) }} qty</span>
                    </div>
                    <div class="h-1 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.06);">
                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background: {{ $barGradients[$i] ?? 'linear-gradient(90deg, #7c3aed, #a78bfa)' }};"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Recent Reservations --}}
    <div class="bg-admin-card rounded-2xl p-5" style="border: 1px solid rgba(255,255,255,0.07);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-slate-100 font-jakarta" style="font-size: 14px;">Reservasi Terbaru</h3>
                <p class="text-[11.5px] text-slate-500 mt-0.5">Data pendaftaran paling akhir masuk</p>
            </div>
            <a href="{{ route('admin.reservations') }}"
               class="flex items-center gap-1.5 text-[11.5px] font-semibold text-violet-400 px-3 py-1.5 rounded-lg transition-all hover:text-violet-300"
               style="background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2);">
                Semua Reservasi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </div>

        @if($recentReservations->isEmpty())
        <div class="py-14 text-center text-slate-600">
            <svg class="w-12 h-12 mx-auto mb-2 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm">Belum ada reservasi masuk</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left" style="font-size: 12.5px;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                        <th class="pb-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-600">Kode</th>
                        <th class="pb-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-600">Pelanggan</th>
                        <th class="pb-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-600 text-center">Tanggal &amp; Waktu</th>
                        <th class="pb-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-600 text-center">Status</th>
                        <th class="pb-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-600 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentReservations as $res)
                    @php
                        $statusConfig = [
                            'pending'   => ['bg' => 'rgba(234,179,8,0.12)',  'color' => '#facc15', 'border' => 'rgba(234,179,8,0.2)',   'label' => 'Pending'],
                            'confirmed' => ['bg' => 'rgba(59,130,246,0.12)', 'color' => '#60a5fa', 'border' => 'rgba(59,130,246,0.2)',  'label' => 'Confirmed'],
                            'completed' => ['bg' => 'rgba(34,197,94,0.12)',  'color' => '#4ade80', 'border' => 'rgba(34,197,94,0.2)',   'label' => 'Completed'],
                            'cancelled' => ['bg' => 'rgba(239,68,68,0.12)', 'color' => '#f87171', 'border' => 'rgba(239,68,68,0.2)',   'label' => 'Cancelled'],
                        ];
                        $sc = $statusConfig[$res->status] ?? ['bg' => 'rgba(255,255,255,0.1)', 'color' => '#94a3b8', 'border' => 'rgba(255,255,255,0.1)', 'label' => ucfirst($res->status)];
                        $isCancelled = $res->status === 'cancelled';
                    @endphp
                    <tr class="transition-colors hover:bg-white/[0.02]" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                        <td class="py-3 font-bold text-violet-400 font-jakarta" style="font-size: 11.5px;">
                            {{ $res->reservation_code ?? '#RES-'.$res->id }}
                        </td>
                        <td class="py-3 pr-3">
                            <div class="flex items-center gap-2">
                                <img src="https://i.pravatar.cc/28?u={{ urlencode($res->customer_name) }}"
                                     alt="{{ $res->customer_name }}"
                                     class="w-7 h-7 rounded-full flex-shrink-0"
                                     style="border: 1.5px solid rgba(255,255,255,0.1);">
                                <span class="font-semibold text-slate-200">{{ $res->customer_name }}</span>
                            </div>
                        </td>
                        <td class="py-3 text-center text-slate-500">
                            {{ $res->reservation_date->format('d M Y') }} &middot; {{ substr($res->reservation_time, 0, 5) }}
                        </td>
                        <td class="py-3 text-center">
                            <span class="text-[10.5px] font-bold px-3 py-1 rounded-full"
                                  style="background: {{ $sc['bg'] }}; color: {{ $sc['color'] }}; border: 1px solid {{ $sc['border'] }};">
                                {{ $sc['label'] }}
                            </span>
                        </td>
                        <td class="py-3 text-right font-bold {{ $isCancelled ? 'text-slate-600 line-through' : 'text-slate-100' }}">
                            Rp {{ number_format($res->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ===== ROW 5: Quick Actions ===== --}}
<div class="bg-admin-card rounded-2xl p-5" style="border: 1px solid rgba(255,255,255,0.07);">
    <h3 class="font-bold text-slate-100 font-jakarta mb-4" style="font-size: 14px;">Aksi Navigasi Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        @foreach([
            [
                'href'    => route('admin.menu'),
                'label'   => 'Kelola Menu Makanan',
                'bg'      => 'rgba(249,115,22,0.08)',
                'border'  => 'rgba(249,115,22,0.15)',
                'color'   => '#fb923c',
                'iconBg'  => 'rgba(249,115,22,0.15)',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
            ],
            [
                'href'    => route('admin.reservations'),
                'label'   => 'Kelola Reservasi',
                'bg'      => 'rgba(139,92,246,0.08)',
                'border'  => 'rgba(139,92,246,0.15)',
                'color'   => '#a78bfa',
                'iconBg'  => 'rgba(139,92,246,0.15)',
                'icon'    => '<rect stroke-linecap="round" stroke-linejoin="round" x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            ],
            [
                'href'    => route('admin.content.promo'),
                'label'   => 'Kelola Promo Restoran',
                'bg'      => 'rgba(236,72,153,0.08)',
                'border'  => 'rgba(236,72,153,0.15)',
                'color'   => '#f472b6',
                'iconBg'  => 'rgba(236,72,153,0.15)',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
            ],
            [
                'href'    => route('admin.customers'),
                'label'   => 'Lihat Pelanggan',
                'bg'      => 'rgba(56,189,248,0.08)',
                'border'  => 'rgba(56,189,248,0.15)',
                'color'   => '#38bdf8',
                'iconBg'  => 'rgba(56,189,248,0.15)',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/>',
            ],
            [
                'href'    => route('admin.reports', ['tab' => 'penjualan']),
                'label'   => 'Laporan Penjualan',
                'bg'      => 'rgba(255,255,255,0.04)',
                'border'  => 'rgba(255,255,255,0.08)',
                'color'   => '#94a3b8',
                'iconBg'  => 'rgba(255,255,255,0.07)',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
            ],
        ] as $action)
        <a href="{{ $action['href'] }}"
           class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-[12.5px] font-semibold transition-all hover:opacity-80 active:scale-95"
           style="background: {{ $action['bg'] }}; border: 1px solid {{ $action['border'] }}; color: {{ $action['color'] }};">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $action['iconBg'] }};">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    {!! $action['icon'] !!}
                </svg>
            </div>
            <span class="leading-snug">{{ $action['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const chartData = @json($chartData);
    const labels = chartData.map(d => d.day + ' (' + d.date + ')');
    const revenueData = chartData.map(d => d.rev_num);
    const itemsData   = chartData.map(d => d.value);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124,58,237,0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'yRevenue',
                    pointBackgroundColor: '#7c3aed',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Item Terjual (Qty)',
                    data: itemsData,
                    borderColor: '#38bdf8',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 4],
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'yQty',
                    pointBackgroundColor: '#38bdf8',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#94a3b8',
                        font: { weight: '600', size: 11 },
                        boxWidth: 24,
                        boxHeight: 2,
                        usePointStyle: false,
                    },
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 10,
                    backgroundColor: '#1a1d2e',
                    borderColor: 'rgba(255,255,255,0.07)',
                    borderWidth: 1,
                    titleColor: '#f1f5f9',
                    bodyColor: '#94a3b8',
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.datasetIndex === 0) {
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            } else {
                                label += context.raw + ' pcs';
                            }
                            return label;
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#475569', font: { size: 10 } },
                    border: { color: 'transparent' },
                },
                yRevenue: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    border: { color: 'transparent', dash: [] },
                    ticks: {
                        color: '#7c3aed',
                        font: { size: 10, weight: '600' },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(v),
                    },
                },
                yQty: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    border: { color: 'transparent' },
                    ticks: {
                        color: '#38bdf8',
                        font: { size: 10, weight: '600' },
                        callback: v => v + ' pcs',
                    },
                },
            },
        },
    });
});
</script>

@endsection
