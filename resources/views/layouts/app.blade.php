<!DOCTYPE html>
<html lang="id" x-data x-bind:class="$store.theme.dark ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $_site['restaurant_name'] ?? 'Restoran') {{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }} — Smart Digital Restaurant</title>
    <meta name="description" content="@yield('description', $_site['description'] ?? 'Restoran modern dengan pengalaman digital terbaik.')">
    @php $faviconUrl = \App\Models\SiteSetting::faviconUrl(); @endphp
    @if($faviconUrl)
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    @else
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Crect width='48' height='48' rx='12' fill='%23f97316'/%3E%3Ccircle cx='24' cy='24' r='13' fill='white' fill-opacity='.95'/%3E%3Cpath d='M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13Z' fill='%23ea580c'/%3E%3Cpath d='M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18' stroke='%23ea580c' stroke-width='1.3' stroke-linecap='round' fill='none'/%3E%3C/svg%3E">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        /* Offset anchor scroll agar tidak tertutup navbar */
        section[id] { scroll-margin-top: 80px; }
    </style>
</head>
<body class="font-sans antialiased">

    {{-- Toast Stack --}}
    <div x-data="toastManager()" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-2 items-end pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-8 scale-95"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 translate-x-8 scale-95"
                class="pointer-events-auto flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl font-medium text-sm max-w-xs"
                :class="{
                    'bg-gray-900 dark:bg-white text-white dark:text-gray-900': toast.type === 'success',
                    'bg-blue-600 text-white': toast.type === 'info',
                    'bg-red-600 text-white': toast.type === 'error',
                }"
            >
                <span x-text="toast.message"></span>
                <button @click="remove(toast.id)" class="ml-1 opacity-60 hover:opacity-100 transition-opacity pointer-events-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    {{-- Cart Sidebar --}}
    @include('components.cart-sidebar')

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

</body>
</html>
