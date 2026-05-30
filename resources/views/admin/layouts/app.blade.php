<!DOCTYPE html>
<html lang="id" class="h-full" x-data x-bind:class="$store.adminTheme.dark ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $_site['restaurant_name'] ?? 'Restoran' }} {{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }} Admin</title>
    @php $faviconUrl = \App\Models\SiteSetting::faviconUrl(); @endphp
    @if($faviconUrl)
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    @else
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Crect width='48' height='48' rx='12' fill='%23f97316'/%3E%3Ccircle cx='24' cy='24' r='13' fill='white' fill-opacity='.95'/%3E%3Cpath d='M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13Z' fill='%23ea580c'/%3E%3Cpath d='M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18' stroke='%23ea580c' stroke-width='1.3' stroke-linecap='round' fill='none'/%3E%3C/svg%3E">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active { @apply bg-violet-600 text-white; }
        /* Mosaic scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 99px; }
    </style>
</head>
<body class="h-full bg-gray-100 dark:bg-slate-900 font-sans antialiased" x-data="{sidebarOpen: false}">

{{-- ===== SIDEBAR ===== --}}
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800 dark:bg-slate-900 flex flex-col transition-transform duration-300 ease-in-out shadow-2xl"
>
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-700">
        @php $logoUrl = \App\Models\SiteSetting::logoUrl(); @endphp
        @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="Logo" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
        @else
        <div class="w-9 h-9 flex-shrink-0">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-lg">
                <rect width="48" height="48" rx="12" fill="url(#sideLogoGrad)"/>
                <path d="M4 4 L10 4 L4 10 Z" fill="white" fill-opacity="0.15"/>
                <path d="M44 4 L38 4 L44 10 Z" fill="white" fill-opacity="0.15"/>
                <path d="M4 44 L10 44 L4 38 Z" fill="white" fill-opacity="0.15"/>
                <path d="M44 44 L38 44 L44 38 Z" fill="white" fill-opacity="0.15"/>
                <circle cx="24" cy="24" r="13" fill="white" fill-opacity="0.95"/>
                <circle cx="24" cy="24" r="10" fill="none" stroke="#f97316" stroke-width="1.2" stroke-opacity="0.4"/>
                <path d="M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13 C19.5 12.5 18.5 12.5 18 13Z" fill="#ea580c"/>
                <path d="M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18" stroke="#ea580c" stroke-width="1.3" stroke-linecap="round" fill="none"/>
                <circle cx="24" cy="24" r="1.5" fill="#f97316" fill-opacity="0.7"/>
                <defs>
                    <linearGradient id="sideLogoGrad" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#ea580c"/>
                        <stop offset="50%" stop-color="#f97316"/>
                        <stop offset="100%" stop-color="#c2410c"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        @endif
        <div class="leading-none">
            <div class="font-bold text-white text-sm leading-tight">{{ $_site['restaurant_name'] ?? 'Restoran' }}</div>
            <div class="font-bold tracking-widest text-[10px] uppercase leading-tight" style="color:#f97316; font-family:'Playfair Display',serif;">{{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }}</div>
        </div>
        {{-- Close on mobile --}}
        <button @click="sidebarOpen=false" class="ml-auto lg:hidden text-slate-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        @php
        $user = auth()->user();
        $navGroups = [
            ['label' => 'DASHBOARD', 'items' => [
                ['route' => 'admin.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
            ]],
            ['label' => 'MANAJEMEN', 'items' => [
                ['route' => 'admin.menu',         'icon' => 'menu',     'label' => 'Menu Makanan'],
                ['route' => 'admin.reservations', 'icon' => 'calendar', 'label' => 'Reservasi'],
                ['route' => 'admin.employees.index', 'icon' => 'users', 'label' => 'Karyawan'],
            ]],
            // HR System removed
            // Laporan & Pengaturan hanya untuk admin dengan permission view_reports
            ...($user && $user->hasPermission('view_reports') ? [
                ['label' => 'LAPORAN', 'items' => [
                    ['route' => 'admin.reports', 'icon' => 'chart',    'label' => 'Penjualan',  'query' => ['tab' => 'penjualan']],
                    ['route' => 'admin.reports', 'icon' => 'calendar', 'label' => 'Kehadiran',  'query' => ['tab' => 'absensi']],
                    ['route' => 'admin.reports', 'icon' => 'orders',   'label' => 'Reservasi',  'query' => ['tab' => 'reservasi']],
                ]],
                ['label' => 'SISTEM', 'items' => [
                    ['route' => 'admin.activity-log', 'icon' => 'orders',   'label' => 'Activity Log'],
                    ['route' => 'admin.settings',     'icon' => 'settings', 'label' => 'Pengaturan'],
                ]],
            ] : []),
        ];
        @endphp

        @foreach($navGroups as $group)
        <div class="mb-4">
            <p class="text-slate-500 text-xs font-semibold tracking-widest px-3 mb-2">{{ $group['label'] }}</p>
            @foreach($group['items'] as $item)
            @php
                $isActive = request()->routeIs($item['route'])
                    && (!isset($item['query']) || request()->get('tab') === ($item['query']['tab'] ?? null));
                $href = isset($item['query'])
                    ? route($item['route'], $item['query'])
                    : route($item['route']);
            @endphp
            <a href="{{ $href }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group mb-0.5
                    {{ $isActive ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/30' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}"
            >
                <span class="w-5 h-5 flex-shrink-0">
                    @include('admin.partials.icon', ['name' => $item['icon'], 'active' => $isActive])
                </span>
                <span class="flex-1">{{ $item['label'] }}</span>
                @if(isset($item['badge']))
                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-violet-600/20 text-violet-400' }}">
                    {{ $item['badge'] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>
        @endforeach
    </nav>

    {{-- User Profile --}}
    <div class="px-3 py-4 border-t border-slate-700">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-700 transition-all cursor-pointer">
            <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">A</div>
            <div class="flex-1 min-w-0">
                <div class="text-white text-sm font-medium truncate">{{ session('admin_name', 'Admin') }}</div>
                <div class="text-slate-400 text-xs truncate">admin@warung.id</div>
            </div>
            <a href="{{ route('admin.logout') }}" title="Logout"
                class="text-slate-400 hover:text-red-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </a>
        </div>
    </div>
</aside>

{{-- Sidebar Overlay (mobile) --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed inset-0 z-40 bg-black/50 lg:hidden" x-cloak></div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="lg:pl-64 flex flex-col min-h-screen">

    {{-- Top Bar --}}
    <header class="sticky top-0 z-30 bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center justify-between px-4 md:px-6 h-16">
            {{-- Left --}}
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen=true" class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-gray-900 dark:text-white font-bold text-lg leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-gray-500 dark:text-slate-400 text-xs">@yield('page-subtitle', 'Selamat datang kembali!')</p>
                </div>
            </div>

            {{-- Right --}}
            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="hidden md:flex items-center gap-2 bg-gray-100 dark:bg-slate-700 rounded-xl px-3 py-2 w-48">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                    <input type="text" placeholder="Cari..." class="bg-transparent text-sm text-gray-700 dark:text-slate-300 placeholder-gray-400 outline-none w-full">
                </div>

                {{-- Dark Mode --}}
                <button
                    x-data
                    @click="$store.adminTheme.toggle()"
                    class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all"
                >
                    <span x-show="!$store.adminTheme.dark">🌙</span>
                    <span x-show="$store.adminTheme.dark">☀️</span>
                </button>

                {{-- Notifications --}}
                @include('components.notification-bell')

                {{-- View Site --}}
                <a href="{{ route('home') }}" target="_blank"
                    class="hidden md:flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat Website
                </a>
                {{-- Logout --}}
                <a href="{{ route('admin.logout') }}"
                    class="hidden md:flex items-center gap-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-sm font-medium px-3 py-2 rounded-xl transition-all"
                    title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1 p-4 md:p-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 text-center text-xs text-gray-400">
        © {{ date('Y') }} Restoran NUSANTARA Admin Panel — v1.0.0
    </footer>
</div>

<script>
// Admin dark mode store (terpisah dari frontend)
document.addEventListener('alpine:init', () => {
    Alpine.store('adminTheme', {
        dark: JSON.parse(localStorage.getItem('admin_dark') || 'false'),
        toggle() { this.dark = !this.dark; this.apply(); localStorage.setItem('admin_dark', this.dark); },
        apply() { document.documentElement.classList.toggle('dark', this.dark); },
        init() { this.apply(); }
    });
});
</script>
</body>
</html>
