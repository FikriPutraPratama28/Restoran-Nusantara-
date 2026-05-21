<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Restoran NUSANTARA</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Crect width='48' height='48' rx='12' fill='%23f97316'/%3E%3Ccircle cx='24' cy='24' r='13' fill='white' fill-opacity='.95'/%3E%3Cpath d='M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13Z' fill='%23ea580c'/%3E%3Cpath d='M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18' stroke='%23ea580c' stroke-width='1.3' stroke-linecap='round' fill='none'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="h-full bg-slate-900 font-sans antialiased">

<div class="min-h-screen flex" x-data="{ showPass: false, loading: false }">

    {{-- ===== LEFT PANEL — Branding ===== --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <img
            src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=900&h=1200&fit=crop"
            alt="Restaurant"
            class="absolute inset-0 w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-br from-violet-900/90 via-purple-900/80 to-slate-900/90"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            {{-- Left Panel Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 flex-shrink-0">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <rect width="48" height="48" rx="12" fill="url(#loginLogoGrad)"/>
                        <path d="M4 4 L10 4 L4 10 Z" fill="white" fill-opacity="0.2"/>
                        <path d="M44 4 L38 4 L44 10 Z" fill="white" fill-opacity="0.2"/>
                        <path d="M4 44 L10 44 L4 38 Z" fill="white" fill-opacity="0.2"/>
                        <path d="M44 44 L38 44 L44 38 Z" fill="white" fill-opacity="0.2"/>
                        <circle cx="24" cy="24" r="13" fill="white" fill-opacity="0.95"/>
                        <circle cx="24" cy="24" r="10" fill="none" stroke="#f97316" stroke-width="1.2" stroke-opacity="0.4"/>
                        <path d="M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13 C19.5 12.5 18.5 12.5 18 13Z" fill="#ea580c"/>
                        <path d="M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18" stroke="#ea580c" stroke-width="1.3" stroke-linecap="round" fill="none"/>
                        <circle cx="24" cy="24" r="1.5" fill="#f97316" fill-opacity="0.7"/>
                        <defs>
                            <linearGradient id="loginLogoGrad" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#ea580c"/>
                                <stop offset="50%" stop-color="#f97316"/>
                                <stop offset="100%" stop-color="#c2410c"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="leading-none">
                    <div class="text-white font-bold text-lg leading-tight">Restoran</div>
                    <div class="font-bold tracking-widest text-[11px] uppercase leading-tight" style="color:#f97316; font-family:'Playfair Display',serif;">NUSANTARA</div>
                </div>
            </div>

            {{-- Headline --}}
            <div>
                <h1 class="text-white font-bold text-4xl leading-tight mb-4">
                    Kelola Restoran<br>
                    <span class="text-violet-300">Lebih Mudah</span>
                </h1>
                <p class="text-white/60 text-base leading-relaxed mb-8">
                    Dashboard lengkap untuk memantau pesanan, reservasi, menu, dan laporan bisnis restoran kamu secara real-time.
                </p>
                <div class="space-y-3">
                    @foreach([
                        ['icon'=>'📊','text'=>'Laporan & analitik real-time'],
                        ['icon'=>'🛒','text'=>'Manajemen pesanan otomatis'],
                        ['icon'=>'📅','text'=>'Sistem reservasi meja digital'],
                        ['icon'=>'🍽️','text'=>'Kelola menu dengan mudah'],
                    ] as $f)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center text-base">{{ $f['icon'] }}</div>
                        <span class="text-white/80 text-sm">{{ $f['text'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                @foreach([['val'=>'2.8K+','label'=>'Pesanan/bulan'],['val'=>'98%','label'=>'Kepuasan'],['val'=>'4.9★','label'=>'Rating']] as $s)
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                    <div class="text-white font-bold text-xl">{{ $s['val'] }}</div>
                    <div class="text-white/50 text-xs mt-0.5">{{ $s['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== RIGHT PANEL — Form ===== --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12">
        <div class="w-full max-w-md">

            {{-- Mobile Logo --}}
            <div class="flex items-center gap-3 mb-10 lg:hidden">
                <div class="w-10 h-10 flex-shrink-0">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <rect width="48" height="48" rx="12" fill="url(#mobileLogoGrad)"/>
                        <circle cx="24" cy="24" r="13" fill="white" fill-opacity="0.95"/>
                        <path d="M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13Z" fill="#ea580c"/>
                        <path d="M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18" stroke="#ea580c" stroke-width="1.3" stroke-linecap="round" fill="none"/>
                        <defs>
                            <linearGradient id="mobileLogoGrad" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#ea580c"/>
                                <stop offset="100%" stop-color="#c2410c"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="leading-none">
                    <div class="text-white font-bold leading-tight">Restoran</div>
                    <div class="font-bold tracking-widest text-[10px] uppercase leading-tight" style="color:#f97316; font-family:'Playfair Display',serif;">NUSANTARA</div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-white text-3xl font-bold mb-2">Selamat Datang 👋</h2>
                <p class="text-slate-400">Masuk ke dashboard admin restoran</p>
            </div>

            {{-- Demo Credentials --}}
            <div class="bg-violet-900/40 border border-violet-700/50 rounded-2xl p-4 mb-6">
                <p class="text-violet-300 text-xs font-semibold mb-2">🔑 Demo Credentials</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-300 text-sm">Email: <span class="text-violet-300 font-mono">admin@warung.id</span></p>
                        <p class="text-slate-300 text-sm">Password: <span class="text-violet-300 font-mono">admin123</span></p>
                    </div>
                    {{-- Auto-fill button --}}
                    <button type="button"
                        onclick="document.getElementById('email').value='admin@warung.id'; document.getElementById('password').value='admin123';"
                        class="text-xs bg-violet-600 hover:bg-violet-700 text-white px-3 py-1.5 rounded-lg transition-all">
                        Isi Otomatis
                    </button>
                </div>
            </div>

            {{-- Error dari Laravel --}}
            @if ($errors->any())
            <div class="bg-red-900/40 border border-red-700/50 rounded-2xl p-4 mb-5 flex items-start gap-3">
                <span class="text-red-400 text-lg flex-shrink-0">⚠️</span>
                <div>
                    @foreach ($errors->all() as $error)
                    <p class="text-red-300 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Success message --}}
            @if (session('error'))
            <div class="bg-red-900/40 border border-red-700/50 rounded-2xl p-4 mb-5">
                <p class="text-red-300 text-sm">{{ session('error') }}</p>
            </div>
            @endif

            {{-- ===== FORM — POST ke Laravel ===== --}}
            <form
                method="POST"
                action="{{ route('admin.login.post') }}"
                @submit="loading = true"
                class="space-y-4"
            >
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-300 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="admin@warung.id"
                            autocomplete="email"
                            required
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-700' }} rounded-2xl text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            id="password"
                            name="password"
                            :type="showPass ? 'text' : 'password'"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            class="w-full pl-12 pr-12 py-3.5 bg-slate-800 border border-slate-700 rounded-2xl text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all"
                        >
                        <button type="button" @click="showPass = !showPass"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-violet-600 focus:ring-violet-500 focus:ring-offset-slate-900">
                        <span class="text-sm text-slate-400">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-3.5 bg-violet-600 hover:bg-violet-700 active:bg-violet-800 disabled:opacity-70 disabled:cursor-not-allowed text-white font-semibold rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 mt-2 shadow-lg shadow-violet-600/30"
                >
                    <svg x-show="loading" class="w-5 h-5 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="loading ? 'Memverifikasi...' : 'Masuk ke Dashboard'"></span>
                </button>
            </form>

            {{-- Back to site --}}
            <div class="mt-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-300 text-sm transition-colors group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Kembali ke Website
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
