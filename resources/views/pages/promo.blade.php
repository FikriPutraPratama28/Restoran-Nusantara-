@extends('layouts.app')

@section('title', 'Promo & Diskon — Warung Nusantara')

@section('content')

<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-warning mb-3">Penawaran Spesial</span>
        <h1 class="font-display text-4xl md:text-6xl font-bold text-white mb-4">
            Promo & <span class="gradient-text">Diskon</span>
        </h1>
        <p class="text-gray-400 max-w-xl mx-auto">
            Hemat lebih banyak dengan promo eksklusif kami setiap hari
        </p>
    </div>
</div>

<section class="section bg-gray-50 dark:bg-dark-900">
    <div class="container-custom">

        {{-- Active Promos --}}
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Promo Aktif</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $promos = [
                    ['gradient' => 'from-primary-600 to-orange-500', 'title' => 'Diskon 30% Makanan', 'desc' => 'Berlaku setiap hari Senin untuk semua menu makanan', 'code' => 'SENIN30', 'expiry' => 'Setiap Senin', 'badge' => 'Mingguan'],
                    ['gradient' => 'from-purple-600 to-pink-500', 'title' => 'Buy 1 Get 1 Minuman', 'desc' => 'Setiap weekend pukul 14.00-17.00 WIB', 'code' => 'WEEKEND2X', 'expiry' => 'Sabtu & Minggu', 'badge' => 'Weekend'],
                    ['gradient' => 'from-green-600 to-teal-500', 'title' => 'Free Dessert', 'desc' => 'Gratis dessert untuk pembelian min. Rp 100.000', 'code' => 'FREEDESSERT', 'expiry' => 'Berlaku terus', 'badge' => 'Permanen'],
                    ['gradient' => 'from-blue-600 to-cyan-500', 'title' => 'Diskon 15% New User', 'desc' => 'Khusus untuk pelanggan baru yang pertama kali order', 'code' => 'NEWUSER', 'expiry' => 'Sekali pakai', 'badge' => 'New User'],
                    ['gradient' => 'from-red-600 to-rose-500', 'title' => 'Potongan Rp 20.000', 'desc' => 'Potongan langsung untuk pembelian min. Rp 75.000', 'code' => 'GRATIS20', 'expiry' => 'Berlaku terus', 'badge' => 'Cashback'],
                    ['gradient' => 'from-yellow-500 to-amber-500', 'title' => 'Member Diskon 10%', 'desc' => 'Diskon 10% untuk semua member terdaftar', 'code' => 'HEMAT10', 'expiry' => 'Berlaku terus', 'badge' => 'Member'],
                ];
                @endphp

                @foreach($promos as $promo)
                <div
                    class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $promo['gradient'] }} p-6 text-white group hover:scale-105 transition-transform duration-300"
                    x-data="{ copied: false }"
                >
                    <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-4 bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>

                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-3">
                            <span class="badge bg-white/20 text-white text-xs">{{ $promo['badge'] }}</span>
                        </div>
                        <h3 class="text-lg font-bold mb-2">{{ $promo['title'] }}</h3>
                        <p class="text-white/80 text-sm mb-4">{{ $promo['desc'] }}</p>

                        <div class="flex items-center gap-2">
                            <div class="bg-white/20 rounded-lg px-3 py-2 flex-1">
                                <span class="font-mono font-bold text-sm">{{ $promo['code'] }}</span>
                            </div>
                            <button
                                @click="
                                    navigator.clipboard.writeText('{{ $promo['code'] }}');
                                    copied = true;
                                    setTimeout(() => copied = false, 2000);
                                "
                                class="bg-white/20 hover:bg-white/30 rounded-lg px-3 py-2 text-sm font-medium transition-all"
                            >
                                <span x-show="!copied">Salin</span>
                                <span x-show="copied">✓ Disalin!</span>
                            </button>
                        </div>

                        <div class="mt-3 text-white/60 text-xs flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $promo['expiry'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Loyalty Program --}}
        <div class="card p-8 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-800 dark:to-dark-700 text-white">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="badge bg-yellow-500/20 text-yellow-400 mb-4">Loyalty Program</span>
                    <h2 class="font-display text-3xl font-bold mb-4">Kumpulkan Poin, Dapatkan Hadiah!</h2>
                    <p class="text-gray-400 mb-6">
                        Setiap pembelian Rp 10.000 = 1 poin. Tukarkan poin kamu dengan diskon, menu gratis, dan hadiah menarik lainnya.
                    </p>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        @foreach([
                            ['points' => '100', 'reward' => 'Diskon 5%'],
                            ['points' => '250', 'reward' => 'Free Minuman'],
                            ['points' => '500', 'reward' => 'Free Makanan'],
                        ] as $tier)
                        <div class="bg-white/10 rounded-xl p-3 text-center">
                            <div class="text-yellow-400 font-bold text-lg">{{ $tier['points'] }}</div>
                            <div class="text-xs text-gray-400">poin</div>
                            <div class="text-white text-xs font-medium mt-1">{{ $tier['reward'] }}</div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('menu') }}" class="btn bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold">
                        Mulai Kumpulkan Poin
                    </a>
                </div>
                <div class="text-center">
                    <div class="w-28 h-28 bg-yellow-100 dark:bg-yellow-900/20 rounded-3xl flex items-center justify-center mx-auto">
                        <svg class="w-14 h-14 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="mt-4 text-gray-400 text-sm">Bergabung dengan 10.000+ member aktif</div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
