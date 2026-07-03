<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center font-sans">
    <div class="text-center px-6">
        <div class="text-8xl mb-6">🔍</div>
        <h1 class="text-6xl font-bold text-white mb-3">404</h1>
        <h2 class="text-xl font-semibold text-slate-300 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-slate-400 text-sm mb-8 max-w-sm mx-auto">
            Halaman yang Anda cari tidak ada atau sudah dipindahkan.
        </p>
        <div class="flex gap-3 justify-center">
            <a href="{{ url()->previous() }}"
                class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-all">
                ← Kembali
            </a>
            <a href="{{ route('home') }}"
                class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">
                🏠 Beranda
            </a>
        </div>
    </div>
</body>
</html>
