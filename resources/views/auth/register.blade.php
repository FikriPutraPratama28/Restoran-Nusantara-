<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Restoran NUSANTARA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="h-full bg-slate-900 font-sans antialiased">

<div class="min-h-screen flex items-center justify-center p-6" x-data="{ showPass: false, showConfirm: false, loading: false }">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl flex items-center justify-center text-2xl shadow-lg">🍽️</div>
            <div>
                <div class="text-white font-bold text-lg leading-tight">Restoran</div>
                <div class="text-orange-400 font-bold tracking-widest text-[11px] uppercase">NUSANTARA</div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-3xl p-8 border border-slate-700 shadow-2xl">
            <div class="mb-6">
                <h2 class="text-white text-2xl font-bold mb-1">Buat Akun Baru</h2>
                <p class="text-slate-400 text-sm">Daftar sebagai pelanggan restoran</p>
            </div>

            {{-- Alert --}}
            @if ($errors->any())
            <div class="bg-red-900/40 border border-red-700/50 rounded-2xl p-4 mb-5">
                @foreach ($errors->all() as $error)<p class="text-red-300 text-sm">⚠️ {{ $error }}</p>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" @submit="loading = true" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Nama Lengkap</label>
                    <input name="name" type="text" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required
                        class="w-full px-4 py-3 bg-slate-700 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-600' }} rounded-xl text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="email@example.com" required
                        class="w-full px-4 py-3 bg-slate-700 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-600' }} rounded-xl text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all">
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">No. HP <span class="text-slate-500 font-normal">(opsional)</span></label>
                    <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <input name="password" :type="showPass ? 'text' : 'password'" placeholder="Min. 8 karakter" required
                            class="w-full pr-12 px-4 py-3 bg-slate-700 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-600' }} rounded-xl text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                            <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input name="password_confirmation" :type="showConfirm ? 'text' : 'password'" placeholder="Ulangi password" required
                            class="w-full pr-12 px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                            <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" :disabled="loading"
                    class="w-full py-3.5 bg-violet-600 hover:bg-violet-700 disabled:opacity-70 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 mt-2">
                    <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="loading ? 'Mendaftarkan...' : 'Buat Akun'"></span>
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-slate-500 text-sm">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300 font-semibold transition-colors">Masuk sekarang</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-300 text-sm transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                Kembali ke Website
            </a>
        </div>
    </div>
</div>

</body>
</html>
