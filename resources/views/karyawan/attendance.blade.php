@extends('karyawan.layouts.app')
@section('title', 'Absensi')
@section('page-title', 'Absensi')
@section('page-subtitle', 'Check in & check out harian')

@section('content')

{{-- ===== TOMBOL ABSENSI — Form HTML biasa, tanpa JavaScript ===== --}}
@if(!$todayAttend || !$todayAttend->check_out)
<div class="max-w-2xl mx-auto mb-4">

@if(session('absen_success'))
    <div class="mb-4 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 font-semibold text-sm flex items-center gap-3">
        <span class="text-xl">✅</span> {{ session('absen_success') }}
    </div>
    @if(session('absen_tts'))
    <script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (!window.speechSynthesis) return;
            const nama = {{ json_encode(explode(' ', auth()->user()->name)[0]) }};
            const type = '{{ session('absen_tts') }}';
            const teks = type === 'checkin'
                ? `Terima kasih sudah absen, ${nama}. Selamat bekerja!`
                : `Terima kasih sudah absen, ${nama}. Selamat beristirahat!`;
            const u = new SpeechSynthesisUtterance(teks);
            u.lang = 'id-ID'; u.rate = 0.95; u.pitch = 1.1; u.volume = 1.0;
            const voices = window.speechSynthesis.getVoices();
            const v = voices.find(v => v.lang === 'id-ID' || v.lang.startsWith('id'));
            if (v) u.voice = v;
            window.speechSynthesis.speak(u);
        }, 500);
    });
    </script>
    @endif
    @endif

    @if(session('absen_error'))
    <div class="mb-4 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 font-semibold text-sm flex items-center gap-3">
        <span class="text-xl">❌</span> {{ session('absen_error') }}
    </div>
    @endif

    @if(!$todayAttend)
    {{-- FORM CHECK IN --}}
    <form method="POST" action="{{ route('karyawan.attendance.checkin.web') }}">
        @csrf
        <button type="submit"
            class="w-full py-6 rounded-2xl font-bold text-2xl text-white bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 transition-all shadow-xl shadow-emerald-500/40 cursor-pointer">
            ✅&nbsp;&nbsp;CHECK IN SEKARANG
        </button>
    </form>
    <p class="text-center text-sm text-gray-400 mt-2">Klik tombol hijau di atas untuk mencatat kehadiran Anda</p>

    @else
    {{-- FORM CHECK OUT --}}
    <form method="POST" action="{{ route('karyawan.attendance.checkout.web') }}">
        @csrf
        <button type="submit"
            class="w-full py-6 rounded-2xl font-bold text-2xl text-white bg-blue-500 hover:bg-blue-600 active:bg-blue-700 transition-all shadow-xl shadow-blue-500/40 cursor-pointer">
            🏠&nbsp;&nbsp;CHECK OUT SEKARANG
        </button>
    </form>
    <p class="text-center text-sm text-gray-400 mt-2">
        Check in: <strong class="text-gray-700 dark:text-slate-200">{{ substr($todayAttend->check_in, 0, 5) }}</strong>
        — Klik tombol biru di atas untuk pulang
    </p>
    @endif

</div>
@endif

<div x-data="absensiApp()" x-init="init()" class="max-w-2xl mx-auto space-y-5">

    {{-- ===== STATUS HARI INI ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-violet-600 px-6 py-4">
            <p class="text-blue-100 text-sm font-medium">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            <h2 class="text-white text-xl font-bold mt-0.5">Status Absensi Hari Ini</h2>
        </div>
        <div class="grid grid-cols-2 divide-x divide-gray-100 dark:divide-slate-700">
            {{-- Check In --}}
            <div class="p-5 text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-2">Check In</p>
                @if($todayAttend?->check_in)
                    <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                        {{ substr($todayAttend->check_in, 0, 5) }}
                    </div>
                    @if($todayAttend->status === 'terlambat')
                        <span class="inline-block mt-2 text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 px-3 py-1 rounded-full">
                            ⏰ Terlambat {{ $todayAttend->late_minutes }} mnt
                        </span>
                    @else
                        <span class="inline-block mt-2 text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 px-3 py-1 rounded-full">
                            ✓ Tepat Waktu
                        </span>
                    @endif
                @else
                    <div class="text-3xl font-bold text-gray-200 dark:text-slate-600 font-mono">--:--</div>
                    <span class="inline-block mt-2 text-xs text-gray-400 dark:text-slate-500">Belum check in</span>
                @endif
            </div>
            {{-- Check Out --}}
            <div class="p-5 text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-2">Check Out</p>
                @if($todayAttend?->check_out)
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 font-mono">
                        {{ substr($todayAttend->check_out, 0, 5) }}
                    </div>
                    @if($todayAttend->work_duration)
                        <span class="inline-block mt-2 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 px-3 py-1 rounded-full">
                            ⏱ {{ $todayAttend->work_duration }}
                        </span>
                    @endif
                @else
                    <div class="text-3xl font-bold text-gray-200 dark:text-slate-600 font-mono">--:--</div>
                    <span class="inline-block mt-2 text-xs text-gray-400 dark:text-slate-500">Belum check out</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== PANEL ABSENSI ===== --}}
    @if(!$todayAttend || !$todayAttend->check_out)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">

        {{-- Jam Digital --}}
        <div class="text-center pt-8 pb-4 px-6 bg-gray-50 dark:bg-slate-700/30">
            <div class="text-6xl font-bold text-gray-900 dark:text-white font-mono tracking-tight" x-text="currentTime"></div>
            <p class="text-gray-400 dark:text-slate-500 text-sm mt-2" x-text="currentDate"></p>

            {{-- GPS Badge --}}
            <div class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-full text-sm font-semibold transition-all pointer-events-none"
                :class="gpsReady
                    ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'
                    : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'">
                <span :class="gpsReady ? 'bg-emerald-500' : 'bg-yellow-500 animate-pulse'" class="w-2.5 h-2.5 rounded-full flex-shrink-0"></span>
                <span x-text="gpsReady ? '📍 Lokasi terdeteksi' : '⏳ Mendapatkan lokasi...'"></span>
            </div>
        </div>

        {{-- TOMBOL SELFIE --}}
            <button @click="showSelfiePanel = !showSelfiePanel; if(showSelfiePanel) $nextTick(() => startCamera())"
                :disabled="loading"
                class="w-full mt-3 py-4 rounded-2xl font-bold text-base text-white bg-violet-500 hover:bg-violet-600 active:bg-violet-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all shadow-lg shadow-violet-500/30 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-3">
                <span class="text-xl">🤳</span>
                <span x-text="showSelfiePanel ? 'Tutup Kamera Selfie' : 'Absen dengan Selfie'"></span>
            </button>

            {{-- PANEL SELFIE (muncul di bawah tombol) --}}
            <div x-show="showSelfiePanel" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="mt-4 bg-violet-50 dark:bg-violet-900/20 border-2 border-violet-200 dark:border-violet-800 rounded-2xl p-5">

                <div class="flex flex-col items-center gap-4">

                    {{-- Preview kamera --}}
                    <div class="relative w-full max-w-xs h-56 rounded-2xl overflow-hidden bg-gray-900 border-4 border-violet-300 dark:border-violet-700">
                        <video id="selfieVideo"
                            class="w-full h-full object-cover"
                            autoplay playsinline muted
                            x-show="cameraActive && !selfiePhoto"></video>
                        <canvas id="selfieCanvas" class="hidden"></canvas>

                        {{-- Hasil foto --}}
                        <div x-show="selfiePhoto" class="absolute inset-0">
                            <img :src="selfiePhoto" class="w-full h-full object-cover">
                            <div class="absolute top-3 right-3 w-9 h-9 bg-emerald-500 rounded-full flex items-center justify-center text-white text-lg shadow-lg font-bold">✓</div>
                        </div>

                        {{-- Placeholder --}}
                        <div x-show="!cameraActive && !selfiePhoto"
                            class="absolute inset-0 flex flex-col items-center justify-center text-white cursor-pointer"
                            @click="startCamera()">
                            <div class="text-5xl mb-2">📷</div>
                            <p class="text-sm font-semibold">Tap untuk buka kamera</p>
                        </div>
                    </div>

                    {{-- Tombol kontrol kamera --}}
                    <div class="flex gap-3 flex-wrap justify-center w-full">
                        <button @click="startCamera()"
                            x-show="!cameraActive && !selfiePhoto"
                            class="flex-1 py-3 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                            📷 Buka Kamera
                        </button>
                        <button @click="takeSelfie()"
                            x-show="cameraActive && !selfiePhoto"
                            class="flex-1 py-3 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-violet-600/30 flex items-center justify-center gap-2">
                            📸 Ambil Foto
                        </button>
                        <button @click="retakeSelfie()"
                            x-show="selfiePhoto"
                            class="py-3 px-5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                            🔄 Ulangi
                        </button>
                    </div>

                    {{-- Tombol submit selfie --}}
                    <button @click="doAbsensiSelfie()"
                        x-show="selfiePhoto"
                        :disabled="loading"
                        class="w-full py-4 rounded-2xl font-bold text-lg text-white transition-all flex items-center justify-center gap-3 disabled:opacity-60 shadow-lg
                        {{ !$todayAttend ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/40' : 'bg-blue-500 hover:bg-blue-600 shadow-blue-500/40' }}">
                        <svg x-show="loading" class="w-5 h-5 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-show="!loading">{{ !$todayAttend ? '✅ CHECK IN dengan Selfie' : '🏠 CHECK OUT dengan Selfie' }}</span>
                        <span x-show="loading">Memproses...</span>
                    </button>
                </div>
            </div>

            {{-- Notifikasi --}}
            <div x-show="notification.show" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                :class="notification.success
                    ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400'
                    : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-400'"
                class="mt-4 p-4 rounded-xl border text-sm font-semibold flex items-center gap-3">
                <span x-text="notification.success ? '✅' : '❌'" class="text-xl flex-shrink-0"></span>
                <span x-text="notification.message"></span>
            </div>
        </div>

        {{-- ===== METODE LAIN: Tab QR / Selfie ===== --}}
        <div class="border-t border-gray-100 dark:border-slate-700">
            <button @click="showExtra = !showExtra"
                class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-all">
                <span class="flex items-center gap-2">
                    <span>🔧</span>
                    <span>Metode lain (QR Code / Selfie)</span>
                </span>
                <svg :class="showExtra ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="showExtra" x-cloak x-transition class="px-6 pb-6 space-y-5">

                {{-- Tab Selector --}}
                <div class="flex gap-1 bg-gray-100 dark:bg-slate-700 p-1 rounded-xl">
                    <button @click="extraTab = 'qr'"
                        :class="extraTab === 'qr' ? 'bg-white dark:bg-slate-800 shadow text-gray-900 dark:text-white font-semibold' : 'text-gray-500 dark:text-slate-400'"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-sm transition-all">
                        <span>📱</span> QR Code
                    </button>
                    <button @click="extraTab = 'selfie'"
                        :class="extraTab === 'selfie' ? 'bg-white dark:bg-slate-800 shadow text-gray-900 dark:text-white font-semibold' : 'text-gray-500 dark:text-slate-400'"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-sm transition-all">
                        <span>🤳</span> Selfie
                    </button>
                </div>

                {{-- QR Code Tab --}}
                <div x-show="extraTab === 'qr'">
                    <div class="flex flex-col sm:flex-row gap-4 items-center bg-gray-50 dark:bg-slate-700/30 rounded-2xl p-5">
                        <div class="bg-white p-3 rounded-xl shadow border border-gray-200 dark:border-slate-600 flex-shrink-0">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($qrToken) }}&bgcolor=ffffff&color=4c1d95"
                                alt="QR Absensi" class="w-36 h-36 rounded-lg">
                        </div>
                        <div class="flex-1 w-full">
                            <p class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1">Scan QR Code di atas</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mb-4">atau masukkan token secara manual:</p>
                            <div class="flex gap-2">
                                <input type="text" x-model="qrInput" placeholder="Paste token QR..."
                                    class="flex-1 px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                                <button @click="doAbsensiQR()" :disabled="loading || !qrInput.trim()"
                                    class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-violet-600/30">
                                    Absen
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-2">Token berlaku untuk hari ini saja</p>
                        </div>
                    </div>
                </div>

                {{-- Selfie Tab --}}
                <div x-show="extraTab === 'selfie'" class="bg-gray-50 dark:bg-slate-700/30 rounded-2xl p-5">
                    <div class="flex flex-col items-center gap-4">

                        {{-- Preview area --}}
                        <div class="relative w-64 h-52 rounded-2xl overflow-hidden bg-gray-900 border-4 border-gray-200 dark:border-slate-600 flex-shrink-0">
                            <video id="selfieVideo"
                                class="w-full h-full object-cover"
                                autoplay playsinline muted
                                x-show="cameraActive && !selfiePhoto"></video>
                            <canvas id="selfieCanvas" class="hidden"></canvas>

                            {{-- Foto hasil --}}
                            <div x-show="selfiePhoto" class="absolute inset-0">
                                <img :src="selfiePhoto" class="w-full h-full object-cover">
                                <div class="absolute top-3 right-3 w-9 h-9 bg-emerald-500 rounded-full flex items-center justify-center text-white text-lg shadow-lg">✓</div>
                            </div>

                            {{-- Placeholder saat kamera belum aktif --}}
                            <div x-show="!cameraActive && !selfiePhoto"
                                class="absolute inset-0 flex flex-col items-center justify-center text-white cursor-pointer"
                                @click="startCamera()">
                                <div class="text-5xl mb-3">📷</div>
                                <p class="text-sm font-semibold">Tap untuk buka kamera</p>
                                <p class="text-xs text-gray-400 mt-1">Izinkan akses kamera di browser</p>
                            </div>
                        </div>

                        {{-- Tombol kamera --}}
                        <div class="flex gap-3 flex-wrap justify-center">
                            <button @click="startCamera()"
                                x-show="!cameraActive && !selfiePhoto"
                                class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                                📷 Buka Kamera
                            </button>
                            <button @click="takeSelfie()"
                                x-show="cameraActive && !selfiePhoto"
                                class="px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-violet-600/30 flex items-center gap-2">
                                📸 Ambil Foto
                            </button>
                            <button @click="retakeSelfie()"
                                x-show="selfiePhoto"
                                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                                🔄 Foto Ulang
                            </button>
                        </div>

                        {{-- Tombol submit selfie --}}
                        <button @click="doAbsensiSelfie()"
                            x-show="selfiePhoto"
                            :disabled="loading"
                            class="w-full py-4 rounded-2xl font-bold text-lg text-white transition-all flex items-center justify-center gap-3 disabled:opacity-60 shadow-lg"
                            :class="{{ !$todayAttend ? "'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/40'" : "'bg-blue-500 hover:bg-blue-600 shadow-blue-500/40'" }}">
                            <svg x-show="loading" class="w-5 h-5 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-show="!loading">{{ !$todayAttend ? '✅ Check In dengan Selfie' : '🏠 Check Out dengan Selfie' }}</span>
                            <span x-show="loading">Memproses...</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @else
    {{-- ===== SELESAI HARI INI ===== --}}
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border-2 border-emerald-200 dark:border-emerald-800 rounded-2xl p-8 text-center">
        <div class="text-7xl mb-4">🎉</div>
        <h3 class="font-bold text-emerald-700 dark:text-emerald-400 text-2xl mb-3">Absensi Selesai!</h3>
        <div class="flex items-center justify-center gap-6 text-sm text-emerald-600 dark:text-emerald-500">
            <div class="text-center">
                <p class="text-xs text-emerald-500 mb-1">Check In</p>
                <p class="text-xl font-bold font-mono">{{ substr($todayAttend->check_in, 0, 5) }}</p>
            </div>
            <div class="text-emerald-300 text-2xl">→</div>
            <div class="text-center">
                <p class="text-xs text-emerald-500 mb-1">Check Out</p>
                <p class="text-xl font-bold font-mono">{{ substr($todayAttend->check_out, 0, 5) }}</p>
            </div>
            <div class="text-emerald-300 text-2xl">·</div>
            <div class="text-center">
                <p class="text-xs text-emerald-500 mb-1">Durasi</p>
                <p class="text-xl font-bold">{{ $todayAttend->work_duration }}</p>
            </div>
        </div>
        <p class="text-emerald-500 dark:text-emerald-600 text-sm mt-4">Sampai jumpa besok! 👋</p>
    </div>
    @endif

    {{-- ===== RIWAYAT ABSENSI ===== --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-7 h-7 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center text-sm">📋</span>
                Riwayat Absensi
            </h3>
            <span class="text-xs text-gray-400 dark:text-slate-500 bg-gray-100 dark:bg-slate-700 px-2.5 py-1 rounded-full">{{ $history->total() }} total</span>
        </div>
        @if($history->isEmpty())
        <div class="py-12 text-center">
            <div class="text-4xl mb-3">📋</div>
            <p class="text-gray-400 dark:text-slate-500 text-sm">Belum ada riwayat absensi</p>
        </div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($history as $att)
            @php $sb = $att->status_badge; @endphp
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <div class="w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300 leading-none">{{ $att->date->format('d') }}</span>
                    <span class="text-[10px] text-gray-400 dark:text-slate-500 uppercase">{{ $att->date->format('M') }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 dark:text-slate-200">{{ $att->date->locale('id')->isoFormat('dddd') }}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 font-mono mt-0.5">
                        CI: {{ $att->check_in ? substr($att->check_in,0,5) : '—' }}
                        &nbsp;·&nbsp;
                        CO: {{ $att->check_out ? substr($att->check_out,0,5) : '—' }}
                        @if($att->work_duration) &nbsp;·&nbsp; {{ $att->work_duration }} @endif
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($att->check_in_photo_url || $att->check_out_photo_url)
                    <span class="text-violet-400 text-sm" title="Ada foto selfie">📷</span>
                    @endif
                    @if($att->check_in_lat)
                    <span class="text-blue-400 text-sm" title="Ada data GPS">📍</span>
                    @endif
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $sb['class'] }}">{{ $sb['label'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
            {{ $history->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Tunggu DOM siap sebelum definisikan fungsi
document.addEventListener('DOMContentLoaded', function() {

// ── Vanilla JS untuk tombol utama ────────────────────────────────────────
const CHECKIN_URL   = '{{ route('karyawan.attendance.checkin') }}';
const CHECKOUT_URL  = '{{ route('karyawan.attendance.checkout') }}';
const HAS_CHECKEDIN = {{ $todayAttend && !$todayAttend->check_out ? 'true' : 'false' }};
const NAMA_KARYAWAN = {{ json_encode(explode(' ', auth()->user()->name)[0]) }};
const CSRF_TOKEN    = '{{ csrf_token() }}';

window.doAbsensiClick = function() {
    const btn = document.getElementById('btnAbsensi');
    if (!btn || btn.disabled) return;

    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.style.cursor  = 'not-allowed';
    btn.innerHTML     = '⏳&nbsp;&nbsp;Memproses...';

    const doFetch = (lat, lng) => {
        const url  = HAS_CHECKEDIN ? CHECKOUT_URL : CHECKIN_URL;
        const body = { latitude: lat, longitude: lng };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify(body)
        })
        .then(r => r.json())
        .then(json => {
            if (json.success) {
                speakAbsen(HAS_CHECKEDIN ? 'checkout' : 'checkin');
                btn.style.opacity   = '1';
                btn.style.background = '#10b981';
                btn.innerHTML = '✅&nbsp;&nbsp;' + json.message;
                setTimeout(() => window.location.reload(), 3500);
            } else {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor  = 'pointer';
                btn.innerHTML = HAS_CHECKEDIN
                    ? '🏠&nbsp;&nbsp;CHECK OUT SEKARANG'
                    : '✅&nbsp;&nbsp;CHECK IN SEKARANG';
                const notifEl = document.getElementById('notifUtama');
                if (notifEl) {
                    notifEl.textContent = json.message;
                    notifEl.className = 'mt-3 p-4 rounded-xl border bg-red-50 border-red-200 text-red-700 text-sm font-semibold';
                    notifEl.style.display = 'block';
                    setTimeout(() => notifEl.style.display = 'none', 5000);
                }
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor  = 'pointer';
            btn.innerHTML = HAS_CHECKEDIN
                ? '🏠&nbsp;&nbsp;CHECK OUT SEKARANG'
                : '✅&nbsp;&nbsp;CHECK IN SEKARANG';
            const notifEl = document.getElementById('notifUtama');
            if (notifEl) {
                notifEl.textContent = 'Terjadi kesalahan koneksi. Coba lagi.';
                notifEl.className = 'mt-3 p-4 rounded-xl border bg-red-50 border-red-200 text-red-700 text-sm font-semibold';
                notifEl.style.display = 'block';
            }
        });
    };

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => doFetch(pos.coords.latitude, pos.coords.longitude),
            ()  => doFetch(null, null),
            { timeout: 5000 }
        );
    } else {
        doFetch(null, null);
    }
};

window.speakAbsen = function(type) {
    if (!window.speechSynthesis) return;
    window.speechSynthesis.cancel();
    const teks = type === 'checkin'
        ? `Terima kasih sudah absen, ${NAMA_KARYAWAN}. Selamat bekerja!`
        : `Terima kasih sudah absen, ${NAMA_KARYAWAN}. Selamat beristirahat!`;
    const ucapan = new SpeechSynthesisUtterance(teks);
    ucapan.lang   = 'id-ID';
    ucapan.rate   = 0.95;
    ucapan.pitch  = 1.1;
    ucapan.volume = 1.0;
    const voices  = window.speechSynthesis.getVoices();
    const idVoice = voices.find(v => v.lang === 'id-ID' || v.lang.startsWith('id'));
    if (idVoice) ucapan.voice = idVoice;
    window.speechSynthesis.speak(ucapan);
};

if (window.speechSynthesis) {
    window.speechSynthesis.getVoices();
    window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
}

}); // end DOMContentLoaded

function absensiApp() {
    return {
        currentTime: '',
        currentDate: '',
        loading: false,
        gpsReady: false,
        latitude: null,
        longitude: null,
        selfiePhoto: null,
        cameraActive: false,
        videoStream: null,
        qrInput: '',
        notification: { show: false, success: false, message: '' },
        hasCheckedIn: @json((bool)($todayAttend && !$todayAttend->check_out)),
        showExtra: false,
        extraTab: 'qr',
        showSelfiePanel: false,

        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            this.getGPS();
            // Preload daftar suara TTS agar siap saat dipakai
            if (window.speechSynthesis) {
                window.speechSynthesis.getVoices();
                window.speechSynthesis.onvoiceschanged = () => {
                    window.speechSynthesis.getVoices();
                };
            }
        },

        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
            this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        },

        getGPS() {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(
                (pos) => { this.latitude = pos.coords.latitude; this.longitude = pos.coords.longitude; this.gpsReady = true; },
                () => { this.gpsReady = false; },
                { timeout: 10000 }
            );
        },

        async doAbsensi() {
            this.loading = true;
            const url = this.hasCheckedIn
                ? '{{ route('karyawan.attendance.checkout') }}'
                : '{{ route('karyawan.attendance.checkin') }}';
            await this.sendRequest(url, { latitude: this.latitude, longitude: this.longitude });
        },

        async doAbsensiSelfie() {
            this.loading = true;
            const url = this.hasCheckedIn
                ? '{{ route('karyawan.attendance.checkout') }}'
                : '{{ route('karyawan.attendance.checkin') }}';
            await this.sendRequest(url, { photo: this.selfiePhoto, latitude: this.latitude, longitude: this.longitude });
        },

        async doAbsensiQR() {
            if (!this.qrInput.trim()) { this.showNotif(false, 'Masukkan token QR terlebih dahulu.'); return; }
            this.loading = true;
            await this.sendRequest('{{ route('karyawan.attendance.qr') }}', {
                qr_token: this.qrInput.trim(),
                type: this.hasCheckedIn ? 'check_out' : 'check_in',
                latitude: this.latitude,
                longitude: this.longitude
            });
        },

        async sendRequest(url, data) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                const json = await res.json();
                if (json.success) {
                    const isCheckout = this.hasCheckedIn;
                    this.speak(isCheckout ? 'checkout' : 'checkin');
                }
                this.showNotif(json.success, json.message);
                if (json.success) setTimeout(() => window.location.reload(), 3500);
            } catch (e) {
                this.showNotif(false, 'Terjadi kesalahan koneksi. Coba lagi.');
            } finally {
                this.loading = false;
            }
        },

        showNotif(success, message) {
            this.notification = { show: true, success, message };
            if (!success) setTimeout(() => this.notification.show = false, 5000);
        },

        /**
         * Text-to-Speech menggunakan Web Speech API.
         * Menyebut nama karyawan saat absen berhasil.
         */
        speak(type) {
            if (!window.speechSynthesis) return;

            // Ambil nama karyawan dari halaman
            const nama = {{ json_encode(explode(' ', auth()->user()->name)[0]) }};

            let teks = '';
            if (type === 'checkin') {
                teks = `Terima kasih sudah absen, ${nama}. Selamat bekerja!`;
            } else {
                teks = `Terima kasih sudah absen, ${nama}. Selamat beristirahat!`;
            }

            // Batalkan ucapan sebelumnya jika ada
            window.speechSynthesis.cancel();

            const ucapan = new SpeechSynthesisUtterance(teks);
            ucapan.lang   = 'id-ID';   // Bahasa Indonesia
            ucapan.rate   = 0.95;      // Kecepatan bicara (0.1–10)
            ucapan.pitch  = 1.1;       // Nada suara (0–2)
            ucapan.volume = 1.0;       // Volume (0–1)

            // Pilih suara Indonesia jika tersedia
            const voices = window.speechSynthesis.getVoices();
            const idVoice = voices.find(v =>
                v.lang === 'id-ID' || v.lang.startsWith('id')
            );
            if (idVoice) ucapan.voice = idVoice;

            window.speechSynthesis.speak(ucapan);
        },

        async startCamera() {
            try {
                this.videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                document.getElementById('selfieVideo').srcObject = this.videoStream;
                this.cameraActive = true;
            } catch (e) {
                this.showNotif(false, 'Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
            }
        },

        takeSelfie() {
            const video = document.getElementById('selfieVideo');
            const canvas = document.getElementById('selfieCanvas');
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.translate(canvas.width, 0); ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0);
            this.selfiePhoto = canvas.toDataURL('image/jpeg', 0.85);
            if (this.videoStream) { this.videoStream.getTracks().forEach(t => t.stop()); this.cameraActive = false; }
        },

        retakeSelfie() { this.selfiePhoto = null; this.startCamera(); }
    }
}
</script>
@endsection
