<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $_site['restaurant_name'] ?? 'Restoran' }} {{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    <script>
        // Terapkan dark mode sebelum render untuk hindari flash
        (function() {
            if (localStorage.getItem('karyawan_dark') === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body
    x-data="{
        sidebarOpen: false,
        darkMode: localStorage.getItem('karyawan_dark') === 'true',
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('karyawan_dark', this.darkMode);
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }"
    class="h-full bg-gray-50 dark:bg-slate-900 font-sans antialiased">

<div class="flex h-full">

    {{-- ===== SIDEBAR ===== --}}
    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 dark:bg-slate-950 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-800">
            @php $logoUrl = \App\Models\SiteSetting::logoUrl(); @endphp
            @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
            @else
            <div class="w-9 h-9 bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl flex items-center justify-center text-lg shadow-lg flex-shrink-0">🍽️</div>
            @endif
            <div class="leading-none">
                <div class="text-white font-bold text-sm">{{ $_site['restaurant_name'] ?? 'Restoran' }}</div>
                <div class="text-orange-400 font-bold tracking-widest text-[9px] uppercase">{{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }}</div>
            </div>
            <div class="ml-auto">
                <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full font-semibold">Karyawan</span>
            </div>
        </div>

        {{-- User Info --}}
        <div class="px-4 py-4 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatar_url }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border-2 border-slate-700">
                <div class="min-w-0">
                    <p class="text-white font-semibold text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ auth()->user()->employee?->jabatan ?? 'Karyawan' }}</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @php
            $navItems = [
                ['route'=>'karyawan.dashboard',  'icon'=>'🏠', 'label'=>'Dashboard'],
                ['route'=>'karyawan.attendance', 'icon'=>'📍', 'label'=>'Absensi'],
                ['route'=>'karyawan.schedule',   'icon'=>'📅', 'label'=>'Jadwal Kerja'],
                ['route'=>'karyawan.leave',      'icon'=>'🏖️', 'label'=>'Pengajuan Cuti'],
            ];
            @endphp
            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                    {{ request()->routeIs($item['route']) ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- Bottom --}}
        <div class="px-3 py-4 border-t border-slate-800 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                <span class="text-base w-5 text-center">🌐</span> Website
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-red-900/20 transition-all">
                    <span class="text-base w-5 text-center">🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 px-4 sm:px-6 py-4 flex items-center gap-4 flex-shrink-0">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex-1">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-500 dark:text-slate-400 hidden sm:block">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Notifikasi --}}
                @include('components.notification-bell')

                {{-- Dark mode --}}
                <button @click="toggleDark()"
                    class="p-2 rounded-xl text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all"
                    :title="darkMode ? 'Mode Terang' : 'Mode Gelap'">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                {{-- Tanggal --}}
                <div class="hidden sm:block text-right">
                    <p class="text-xs font-semibold text-gray-700 dark:text-slate-300">{{ now()->translatedFormat('l') }}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-4 sm:p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
