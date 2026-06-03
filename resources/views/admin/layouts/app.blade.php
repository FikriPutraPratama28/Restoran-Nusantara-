<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <script>
        if (localStorage.getItem('_x_restaurant_dark_mode') === 'true' ||
            localStorage.getItem('restaurant_dark_mode') === 'true' ||
            (!('_x_restaurant_dark_mode' in localStorage) && !('restaurant_dark_mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
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
        /* Admin scrollbar */
        .admin-scroll::-webkit-scrollbar { width: 4px; }
        .admin-scroll::-webkit-scrollbar-track { background: transparent; }
        .admin-scroll::-webkit-scrollbar-thumb { background: #7c3aed; border-radius: 99px; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #7c3aed; border-radius: 99px; }
        /* Sidebar nav active glow */
        .nav-active {
            background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
    </style>
</head>
<body class="h-full font-sans antialiased bg-slate-50 dark:bg-admin-bg text-slate-800 dark:text-slate-100" x-data="{ sidebarOpen: false }">

{{-- ===== SIDEBAR ===== --}}
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-admin-sidebar flex flex-col transition-transform duration-300 ease-in-out border-r border-slate-200 dark:border-white/5"
>
    {{-- Brand --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-200 dark:border-white/5">
        @php $logoUrl = \App\Models\SiteSetting::logoUrl(); @endphp
        @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="Logo" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
        @else
        <div class="w-9 h-9 flex-shrink-0 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #ea580c, #f97316); box-shadow: 0 4px 12px rgba(249,115,22,0.3);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 11l19-9-9 19-2-8-8-2z"/>
            </svg>
        </div>
        @endif
        <div class="leading-none">
            <div class="font-bold text-gray-900 dark:text-slate-100 text-sm leading-tight font-jakarta">{{ $_site['restaurant_name'] ?? 'Restoran' }}</div>
            <div class="font-extrabold text-[10px] uppercase tracking-widest mt-0.5 font-jakarta" style="color: #f97316;">{{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }}</div>
        </div>
        {{-- Close on mobile --}}
        <button @click="sidebarOpen=false" class="ml-auto lg:hidden text-slate-500 hover:text-slate-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto admin-scroll px-3 py-4">
        @php
        $user = auth()->user();
        $navGroups = [
            ['label' => 'DASHBOARD', 'items' => [
                ['route' => 'admin.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
            ]],
            ['label' => 'MANAJEMEN', 'items' => [
                ['route' => 'admin.menu',         'icon' => 'menu',     'label' => 'Menu Makanan'],
                ['route' => 'admin.reservations', 'icon' => 'calendar', 'label' => 'Reservasi'],
            ]],
            ['label' => 'KONTEN', 'items' => [
                ['route' => 'admin.content.promo',   'icon' => 'promo',  'label' => 'Promo'],
                ['route' => 'admin.content.gallery', 'icon' => 'image',  'label' => 'Galeri'],
                ['route' => 'admin.content.team',    'icon' => 'about',  'label' => 'Tim'],
            ]],
            ...($user && $user->hasPermission('view_reports') ? [
                ['label' => 'LAPORAN', 'items' => [
                    ['route' => 'admin.reports', 'icon' => 'chart',  'label' => 'Penjualan',         'query' => ['tab' => 'penjualan']],
                    ['route' => 'admin.reports', 'icon' => 'orders', 'label' => 'Laporan Reservasi', 'query' => ['tab' => 'reservasi']],
                ]],
                ['label' => 'SISTEM', 'items' => [
                    ['route' => 'admin.activity-log', 'icon' => 'orders',   'label' => 'Activity Log'],
                    ['route' => 'admin.settings',     'icon' => 'settings', 'label' => 'Pengaturan'],
                ]],
            ] : []),
        ];
        @endphp

        @foreach($navGroups as $group)
        <div class="mb-5">
            <p class="text-[9.5px] font-bold tracking-widest uppercase px-2 mb-2 text-gray-400 dark:text-slate-600">{{ $group['label'] }}</p>
            @foreach($group['items'] as $item)
            @php
                $isActive = request()->routeIs($item['route'])
                    && (!isset($item['query']) || request()->get('tab') === ($item['query']['tab'] ?? null));
                $href = isset($item['query'])
                    ? route($item['route'], $item['query'])
                    : route($item['route']);
            @endphp
            <a href="{{ $href }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-150 mb-0.5 group
                   {{ $isActive ? 'nav-active text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}"
            >
                <span class="w-4 h-4 flex-shrink-0">
                    @include('admin.partials.icon', ['name' => $item['icon'], 'active' => $isActive])
                </span>
                <span class="flex-1 truncate">{{ $item['label'] }}</span>
                @if(isset($item['badge']))
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-violet-600/20 text-violet-400' }}">
                    {{ $item['badge'] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>
        @endforeach
    </nav>

    {{-- User Profile --}}
    <div class="px-3 pb-4 pt-3 border-t border-slate-200 dark:border-white/5">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background: linear-gradient(135deg, #7c3aed, #ec4899);">
                {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[13px] font-semibold text-slate-800 dark:text-slate-200 truncate">{{ session('admin_name', 'Administrator') }}</div>
                <div class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? 'admin@restoran.id' }}</div>
            </div>
            <a href="{{ route('admin.logout') }}" title="Logout" class="text-slate-500 hover:text-red-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </div>
</aside>

{{-- Sidebar Overlay (mobile) --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed inset-0 z-40 bg-black/60 lg:hidden" x-cloak></div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="lg:pl-64 flex flex-col min-h-screen">

    {{-- Top Bar --}}
    <header class="sticky top-0 z-30 bg-white dark:bg-admin-sidebar flex items-center justify-between px-5 md:px-7 h-[60px] border-b border-slate-200 dark:border-white/5">
        {{-- Left --}}
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen=true" class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:text-slate-350 hover:bg-slate-100 dark:hover:bg-white/5 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h1 class="font-bold text-[15px] leading-tight text-slate-800 dark:text-slate-100 font-jakarta">@yield('page-title', 'Dashboard')</h1>
                <p class="text-[11.5px] text-slate-500">@yield('page-subtitle', 'Selamat datang kembali!')</p>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-2">
            {{-- Search --}}
            <div class="hidden md:flex items-center gap-2 rounded-xl px-3 py-2 w-48 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5">
                <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" placeholder="Cari sesuatu..."
                    class="bg-transparent text-[12.5px] text-slate-700 dark:text-slate-350 placeholder-slate-400 dark:placeholder-slate-600 outline-none w-full">
            </div>

            {{-- Theme Toggle --}}
            <button @click="$store.theme.toggle()"
                class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-all border border-slate-200 dark:border-white/5"
                title="Ganti Tema">
                <!-- Moon Icon (for Light mode to switch to Dark) -->
                <svg x-show="!$store.theme.dark" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <!-- Sun Icon (for Dark mode to switch to Light) -->
                <svg x-show="$store.theme.dark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>

            {{-- Notifications --}}
            @include('components.notification-bell')

            {{-- View Site --}}
            <a href="{{ route('home') }}" target="_blank"
               class="hidden md:flex items-center gap-2 text-white text-[12.5px] font-semibold px-4 py-2 rounded-xl transition-all hover:opacity-90 active:scale-95 font-jakarta"
               style="background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 4px 12px rgba(124,58,237,0.25);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                Lihat Website
            </a>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1 p-5 md:p-7">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="px-7 py-4 text-center text-xs text-slate-500 border-t border-slate-200 dark:border-white/5">
        &copy; {{ date('Y') }} Restoran NUSANTARA Admin Panel &mdash; v1.0.0
    </footer>
</div>

</body>
</html>
