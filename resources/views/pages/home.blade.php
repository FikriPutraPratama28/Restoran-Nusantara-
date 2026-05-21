
@extends('layouts.app')
@section('title', 'Restoran NUSANTARA — Smart Digital Restaurant')
@section('content')

{{-- ============================================================ --}}
{{-- SECTION: HOME / HERO                                         --}}
{{-- ============================================================ --}}
<section id="home" class="relative min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ $_site['home_bg_image'] ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1920&h=1080&fit=crop' }}"
             alt="Restaurant" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
    </div>

    {{-- Floating Food Images --}}
    <div class="absolute right-10 top-1/2 -translate-y-1/2 hidden lg:block z-10">
        <div class="relative w-80 h-80">
            <div class="absolute top-0 right-0 w-48 h-48 rounded-full overflow-hidden border-4 border-white/20 shadow-2xl animate-float">
                <img src="{{ $_site['home_float_img1'] ?? 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=300&h=300&fit=crop' }}" alt="Food" class="w-full h-full object-cover">
            </div>
            <div class="absolute bottom-0 left-0 w-36 h-36 rounded-full overflow-hidden border-4 border-white/20 shadow-2xl animate-float" style="animation-delay:1s">
                <img src="{{ $_site['home_float_img2'] ?? 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=200&h=200&fit=crop' }}" alt="Food" class="w-full h-full object-cover">
            </div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-28 h-28 rounded-full overflow-hidden border-4 border-primary-500/50 shadow-2xl animate-float" style="animation-delay:0.5s">
                <img src="{{ $_site['home_float_img3'] ?? 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=200&h=200&fit=crop' }}" alt="Food" class="w-full h-full object-cover">
            </div>
        </div>
    </div>

    <div class="container-custom relative z-10 pt-20">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-primary-600/20 border border-primary-500/30 rounded-full px-4 py-2 mb-6 animate-fade-in">
                <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
                <span class="text-primary-300 text-sm font-medium">{{ $_site['home_badge_text'] ?? 'Buka Sekarang · Estimasi 15-30 menit' }}</span>
            </div>
            <h1 class="font-display text-5xl md:text-7xl font-bold text-white leading-tight mb-6 animate-slide-up">
                {{ $_site['home_hero_title'] ?? 'Cita Rasa Nusantara di Ujung Jari' }}
            </h1>
            <p class="text-gray-300 text-lg md:text-xl leading-relaxed mb-8 animate-slide-up" style="animation-delay:.1s">
                {{ $_site['home_hero_subtitle'] ?? 'Pesan makanan favoritmu, reservasi meja, dan nikmati promo eksklusif — semua dalam satu platform digital yang modern.' }}
            </p>
            <div class="flex flex-wrap gap-4 animate-slide-up" style="animation-delay:.2s">
                <a href="#menu" class="nav-scroll btn btn-primary text-base px-8 py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Lihat Menu
                </a>
                <a href="#reservasi" class="nav-scroll btn btn-outline border-white text-white hover:bg-white hover:text-gray-900 text-base px-8 py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Reservasi Meja
                </a>
            </div>
            <div class="flex flex-wrap gap-8 mt-12 animate-slide-up" style="animation-delay:.3s">
                @foreach([['value'=>'500+','label'=>'Menu Tersedia'],['value'=>'10K+','label'=>'Pelanggan Puas'],['value'=>'4.9★','label'=>'Rating Rata-rata'],['value'=>'5 Thn','label'=>'Pengalaman']] as $s)
                <div>
                    <div class="text-2xl font-bold text-white">{{ $s['value'] }}</div>
                    <div class="text-gray-400 text-sm">{{ $s['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <a href="#menu" class="nav-scroll block w-6 h-10 border-2 border-white/40 rounded-full flex items-start justify-center p-1">
            <div class="w-1.5 h-3 bg-white/60 rounded-full animate-pulse"></div>
        </a>
    </div>
</section>

{{-- Features Strip --}}
<div class="bg-primary-600 py-6">
    <div class="container-custom">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([['icon'=>'🚀','title'=>'Order Cepat','desc'=>'Proses 15-30 menit'],['icon'=>'🎁','title'=>'Promo Harian','desc'=>'Diskon hingga 50%'],['icon'=>'⭐','title'=>'Kualitas Premium','desc'=>'Bahan pilihan terbaik'],['icon'=>'📱','title'=>'Order Online','desc'=>'Mudah & praktis']] as $f)
            <div class="flex items-center gap-3 text-white">
                <span class="text-2xl">{{ $f['icon'] }}</span>
                <div>
                    <div class="font-bold text-sm">{{ $f['title'] }}</div>
                    <div class="text-primary-200 text-xs">{{ $f['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- SECTION: MENU                                                --}}
{{-- ============================================================ --}}
<section id="menu" class="section bg-gray-50 dark:bg-dark-900" x-data="menuFilter({{ $menus->toJson() }})">
    <div class="container-custom">

        {{-- Header --}}
        <div class="text-center mb-10">
            <span class="badge badge-primary mb-3">🍽️ Menu Kami</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Pilih Menu <span class="gradient-text">Favoritmu</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Dari makanan berat hingga dessert manis, semua ada di sini</p>
        </div>

        {{-- ===== MENU POPULER (selalu tampil di atas) ===== --}}
        <div x-show="!search && activeCategory === 'all' && priceRange === 'all' && !showOnlyPromo" class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🔥</span>
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">Menu Populer</h3>
                    <span class="text-xs bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 px-2 py-0.5 rounded-full font-semibold">Best Seller</span>
                </div>
                <button @click="activeCategory='all'; sortBy='popular'" class="text-primary-600 text-sm font-semibold hover:underline">Lihat semua →</button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <template x-for="menu in popularMenus" :key="'pop-'+menu.id">
                    <div @click="openDetail(menu)"
                        :class="lastAddedId === menu.id ? 'ring-2 ring-primary-400/30 scale-[1.01]' : ''"
                        class="group cursor-pointer bg-white dark:bg-dark-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-dark-700 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="relative h-28 overflow-hidden">
                            <img :src="menu.image" :alt="menu.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button @click.stop="addToCart(menu)"
                                    class="btn btn-sm bg-white text-gray-900 hover:bg-primary-600 hover:text-white">
                                    + Keranjang
                                </button>
                            </div>
                            <div class="absolute bottom-2 left-2">
                                <span x-show="menu.label==='best-seller'" class="text-[10px] bg-primary-600 text-white px-1.5 py-0.5 rounded-full font-bold">🔥</span>
                                <span x-show="menu.label==='popular'" class="text-[10px] bg-blue-600 text-white px-1.5 py-0.5 rounded-full font-bold">⭐</span>
                            </div>
                            <div x-show="menu.isPromo" class="absolute top-2 right-2">
                                <span class="text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded-full font-bold">PROMO</span>
                            </div>
                        </div>
                        <div class="p-2.5">
                            <p class="text-xs font-bold text-gray-800 dark:text-white truncate" x-text="menu.name"></p>
                            <p class="text-xs text-primary-600 font-semibold mt-0.5" x-text="formatPrice(menu.price)"></p>
                            <div class="flex items-center gap-0.5 mt-1">
                                <span class="text-yellow-400 text-[10px]">★</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400" x-text="menu.rating"></span>
                                <span class="text-[10px] text-gray-400 ml-auto">+</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ===== SEARCH BAR PINTAR ===== --}}
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-dark-700 mb-6">

            {{-- Baris 1: Search + Sort --}}
            <div class="flex flex-col sm:flex-row gap-3 mb-4">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input x-model="search" type="text"
                        placeholder="🔍 Cari menu... contoh: ayam, pedas, kopi"
                        class="input pl-12 pr-10 text-sm w-full">
                    <button x-show="search" @click="search=''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-200 dark:bg-dark-600 hover:bg-gray-300 rounded-full flex items-center justify-center transition-all">
                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <select x-model="sortBy" class="input sm:w-44 text-sm flex-shrink-0">
                    <option value="popular">🔥 Terpopuler</option>
                    <option value="rating">⭐ Rating Tertinggi</option>
                    <option value="price-low">💰 Harga Terendah</option>
                    <option value="price-high">💎 Harga Tertinggi</option>
                    <option value="new">✨ Terbaru</option>
                </select>
            </div>

            {{-- Quick Search Tags --}}
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="text-xs text-gray-400 dark:text-gray-500 self-center mr-1">Cari cepat:</span>
                <template x-for="tag in quickTags" :key="tag.label">
                    <button @click="applyQuickTag(tag)"
                        :class="(search === tag.q && tag.q) || (activeCategory === tag.cat && tag.cat)
                            ? 'bg-primary-600 text-white border-primary-600'
                            : 'bg-gray-50 dark:bg-dark-700 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-dark-600 hover:border-primary-400 hover:text-primary-600'"
                        class="text-xs px-3 py-1.5 rounded-full border font-medium transition-all duration-200"
                        x-text="tag.label">
                    </button>
                </template>
            </div>

            {{-- Baris 2: Kategori --}}
            <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1 mb-4">
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="activeCategory = cat.id"
                        :class="activeCategory===cat.id
                            ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30 border-primary-600'
                            : 'bg-gray-50 dark:bg-dark-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-600 border-gray-200 dark:border-dark-600'"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-xl font-medium text-sm whitespace-nowrap transition-all duration-200 border flex-shrink-0">
                        <span x-text="cat.icon"></span>
                        <span x-text="cat.name"></span>
                    </button>
                </template>
            </div>

            {{-- Baris 3: Filter Harga + Toggle --}}
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Filter Harga:</span>
                <template x-for="pr in priceRanges" :key="pr.id">
                    <button @click="priceRange = pr.id"
                        :class="priceRange === pr.id
                            ? 'bg-emerald-600 text-white border-emerald-600'
                            : 'bg-gray-50 dark:bg-dark-700 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-dark-600 hover:border-emerald-400'"
                        class="text-xs px-3 py-1.5 rounded-full border font-medium transition-all duration-200 whitespace-nowrap"
                        x-text="pr.label">
                    </button>
                </template>
                <div class="ml-auto flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div @click="showOnlyPromo = !showOnlyPromo"
                            :class="showOnlyPromo ? 'bg-red-500' : 'bg-gray-200 dark:bg-dark-600'"
                            class="relative w-9 h-5 rounded-full transition-colors duration-200">
                            <div :class="showOnlyPromo ? 'translate-x-4' : 'translate-x-0.5'"
                                class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></div>
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">Promo saja</span>
                    </label>
                </div>
            </div>

            {{-- Active Filters Summary --}}
            <div x-show="activeFiltersCount > 0" class="mt-3 pt-3 border-t border-gray-100 dark:border-dark-700 flex items-center justify-between">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <span class="font-bold text-primary-600" x-text="activeFiltersCount"></span> filter aktif ·
                    <span class="font-bold text-gray-900 dark:text-white" x-text="filtered.length"></span> menu ditemukan
                    <span x-show="search"> untuk "<span class="text-primary-600 italic" x-text="search"></span>"</span>
                </p>
                <button @click="resetFilters()" class="text-xs text-red-500 hover:text-red-700 font-semibold hover:underline">
                    ✕ Reset semua filter
                </button>
            </div>
        </div>

        {{-- Count (saat tidak ada filter aktif) --}}
        <p x-show="activeFiltersCount === 0" class="text-gray-600 dark:text-gray-400 text-sm mb-6">
            Menampilkan <span class="font-bold text-gray-900 dark:text-white" x-text="filtered.length"></span> menu tersedia
        </p>

        {{-- ===== GRID MENU ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="menu in filtered" :key="menu.id">
                <div @click="openDetail(menu)"
                    :class="lastAddedId === menu.id ? 'ring-2 ring-primary-400/30 scale-[1.01]' : ''"
                    class="card card-hover group animate-fade-in transition-all duration-300">
                    <div class="relative overflow-hidden h-48">
                        <img :src="menu.image" :alt="menu.name"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            <span x-show="menu.label==='best-seller'" class="badge bg-primary-600 text-white text-xs">🔥 Best Seller</span>
                            <span x-show="menu.label==='popular'" class="badge bg-blue-600 text-white text-xs">⭐ Popular</span>
                            <span x-show="menu.isNew" class="badge bg-green-600 text-white text-xs">✨ Baru</span>
                        </div>
                        <div x-show="menu.isPromo" class="absolute top-3 right-3">
                            <span class="badge bg-red-500 text-white text-xs">PROMO</span>
                        </div>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button @click.stop="addToCart(menu)"
                                class="btn bg-white text-gray-900 hover:bg-primary-600 hover:text-white text-sm py-2 px-4 transform scale-90 group-hover:scale-100 transition-all duration-300">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        {{-- Nama dengan highlight pencarian --}}
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1 truncate"
                            x-html="highlight(menu.name)"></h3>
                        {{-- Deskripsi dengan highlight --}}
                        <p class="text-gray-500 dark:text-gray-400 text-xs mb-3 line-clamp-2"
                            x-html="highlight(menu.description || '')"></p>
                        <div class="flex items-center gap-1 mb-3">
                            <template x-for="i in 5" :key="i">
                                <span :class="i<=Math.round(menu.rating)?'text-yellow-400':'text-gray-300'" class="text-sm">★</span>
                            </template>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="`${menu.rating} (${menu.reviews})`"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-primary-600 font-bold" x-text="formatPrice(menu.price)"></div>
                                <div x-show="menu.originalPrice" class="text-gray-400 text-xs line-through"
                                    x-text="menu.originalPrice ? formatPrice(menu.originalPrice) : ''"></div>
                            </div>
                            <button @click.stop="addToCart(menu)"
                                class="w-9 h-9 bg-primary-600 hover:bg-primary-700 text-white rounded-lg flex items-center justify-center transition-all hover:scale-110 shadow-lg shadow-primary-600/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Empty State --}}
            <div x-show="filtered.length === 0" class="col-span-full text-center py-20">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Menu tidak ditemukan</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-2">
                    Tidak ada menu yang cocok dengan
                    <span x-show="search">"<span class="text-primary-600 font-semibold" x-text="search"></span>"</span>
                    <span x-show="activeCategory !== 'all'"> di kategori <span class="font-semibold" x-text="activeCategory"></span></span>
                    <span x-show="priceRange !== 'all'"> dengan filter harga ini</span>
                </p>
                <p class="text-gray-400 text-sm mb-6">Coba kata kunci lain atau hapus beberapa filter</p>
                <button @click="resetFilters()" class="btn btn-primary">
                    Reset Semua Filter
                </button>
            </div>
        </div>
    </div>

        {{-- Detail Popup --}}
        <template x-if="selectedMenu">
            <div x-cloak @click="closeDetail()" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70">
                <div @click.stop class="w-full max-w-5xl overflow-hidden rounded-[2rem] bg-white dark:bg-dark-800 shadow-2xl border border-gray-200 dark:border-dark-700">
                    <div class="relative">
                        <img :src="selectedMenu.image" :alt="selectedMenu.name" class="w-full h-72 object-cover">
                        <button @click="closeDetail()" class="absolute top-4 right-4 w-11 h-11 rounded-full bg-white/90 dark:bg-dark-900/90 flex items-center justify-center shadow-lg hover:bg-white dark:hover:bg-dark-800 transition-all">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-6 p-6">
                        <div>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-primary-600 text-white">Detail Menu</span>
                                <span x-show="selectedMenu.isPromo" class="badge bg-red-500 text-white">PROMO</span>
                                <span x-show="selectedMenu.isNew" class="badge bg-green-600 text-white">BARU</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3" x-text="selectedMenu.name"></h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4" x-text="selectedMenu.description"></p>
                            <div class="flex items-center gap-3 mb-5">
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl bg-gray-100 dark:bg-dark-700 text-sm text-gray-600 dark:text-gray-300">
                                    ⭐ <span x-text="selectedMenu.rating"></span> • <span x-text="selectedMenu.reviews + ' ulasan'"></span>
                                </div>
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl bg-primary-50 text-sm text-primary-600">
                                    <span x-text="formatPrice(selectedMenu.price)"></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-6 text-sm text-gray-600 dark:text-gray-300">
                                <div class="p-4 rounded-3xl bg-gray-50 dark:bg-dark-700">
                                    <div class="font-semibold">Kategori</div>
                                    <div x-text="selectedMenu.category"></div>
                                </div>
                                <div class="p-4 rounded-3xl bg-gray-50 dark:bg-dark-700">
                                    <div class="font-semibold">Label</div>
                                    <div x-text="selectedMenu.label ? selectedMenu.label.replace('-', ' ') : 'Standar'"></div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <button @click.stop="addToCart(selectedMenu)" class="btn btn-primary w-full py-3">Tambah ke Keranjang</button>
                                <button @click="closeDetail()" class="btn btn-outline w-full py-3">Tutup</button>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="rounded-3xl overflow-hidden bg-black">
                                <video :src="detailVideo(selectedMenu)" :poster="selectedMenu.image" controls class="w-full h-64 object-cover bg-black"></video>
                            </div>
                            <div class="rounded-3xl bg-gray-50 dark:bg-dark-700 p-4">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Kenapa menu ini cocok untukmu?</h3>
                                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                    <li>• Bahan segar dan resep asli Nusantara</li>
                                    <li>• Pilihan populer untuk makan siang dan santai</li>
                                    <li>• Tersedia paket hemat & promo spesial</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</section>

{{-- ============================================================ --}}
{{-- SECTION: RESERVASI                                           --}}
{{-- ============================================================ --}}
<section id="reservasi" class="section bg-white dark:bg-dark-800">
    <div class="container-custom">
        <div class="max-w-3xl mx-auto" x-data='reservation(@json($menus ?? []))'>
            <div class="text-center mb-10">
                <span class="badge badge-primary mb-3">Reservasi Meja</span>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Pesan Meja Sekarang</h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Pilih waktu yang tepat dan nikmati pengalaman bersantap yang nyaman</p>
            </div>

            {{-- Success State --}}
            <div x-show="submitted" class="text-center py-16 animate-fade-in">
                <div class="text-8xl mb-6 animate-bounce">🎉</div>
                <h2 class="font-display text-3xl font-bold text-gray-900 dark:text-white mb-4">Reservasi Berhasil!</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-2">
                    Terima kasih, <strong x-text="form.name"></strong>!
                </p>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    Reservasi untuk <strong x-text="form.guests + ' orang'"></strong> pada
                    <strong x-text="form.date"></strong> pukul <strong x-text="form.time"></strong>
                    telah dikonfirmasi. Kami akan mengirim konfirmasi ke email kamu.
                </p>
                <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 inline-block text-left mb-8 shadow-lg">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Kode Reservasi</div>
                    <div class="font-mono text-2xl font-bold text-primary-600">
                        #RES-{{ strtoupper(substr(md5(time()), 0, 8)) }}
                    </div>
                </div>
                <br>
                <button @click="reset()" class="btn btn-primary">
                    Buat Reservasi Baru
                </button>
            </div>

            {{-- Reservation Form --}}
            <div x-show="!submitted">

                {{-- Progress Steps --}}
                <div class="flex items-center justify-center mb-10">
                    <template x-for="(label, i) in ['Pilih Waktu & Meja', 'Pilih Menu & Bayar', 'Detail Tamu', 'Konfirmasi']" :key="i">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center">
                                <div
                                    :class="step > i + 1 ? 'bg-green-500 text-white' : step === i + 1 ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-dark-700 text-gray-500'"
                                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                >
                                    <span x-show="step <= i + 1" x-text="i + 1"></span>
                                    <svg x-show="step > i + 1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-xs mt-1 font-medium"
                                    :class="step === i + 1 ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="label"></span>
                            </div>
                            <div x-show="i < 3" class="w-16 md:w-24 h-0.5 mx-2 mb-4"
                                :class="step > i + 1 ? 'bg-green-500' : 'bg-gray-200 dark:bg-dark-700'"></div>
                        </div>
                    </template>
                </div>

                {{-- Step 1: Date & Time --}}
                <div x-show="step === 1" class="card p-8 animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pilih Tanggal & Waktu</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Date --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                📅 Tanggal Reservasi
                            </label>
                            <input
                                x-model="form.date"
                                type="date"
                                :min="minDate"
                                class="input"
                            >
                        </div>

                        {{-- Guests --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                👥 Jumlah Tamu
                            </label>
                            <div class="flex items-center gap-3">
                                <button
                                    @click="form.guests = Math.max(1, form.guests - 1); form.tableNumber = null"
                                    class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-dark-700 flex items-center justify-center font-bold hover:bg-primary-100 dark:hover:bg-primary-900 transition-all"
                                >−</button>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white w-12 text-center" x-text="form.guests"></span>
                                <button
                                    @click="form.guests = Math.min(20, form.guests + 1); form.tableNumber = null"
                                    class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-dark-700 flex items-center justify-center font-bold hover:bg-primary-100 dark:hover:bg-primary-900 transition-all"
                                >+</button>
                                <span class="text-gray-500 dark:text-gray-400 text-sm">orang</span>
                            </div>
                        </div>
                    </div>

                    {{-- Time Slots --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            🕐 Pilih Jam
                        </label>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                            <template x-for="time in timeSlots" :key="time">
                                <button
                                    @click="form.time = time"
                                    :class="form.time === time
                                        ? 'bg-primary-600 text-white border-primary-600'
                                        : 'bg-white dark:bg-dark-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-dark-600 hover:border-primary-400'"
                                    class="py-2 px-3 rounded-lg border text-sm font-medium transition-all duration-200"
                                    x-text="time"
                                ></button>
                            </template>
                        </div>
                    </div>

                    {{-- Table Area --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            🪑 Area Duduk
                        </label>
                        {{-- Pilih Area --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            @foreach([
                                ['value' => 'indoor', 'icon' => '🏠', 'label' => 'Indoor', 'desc' => 'Ruangan ber-AC'],
                                ['value' => 'outdoor', 'icon' => '🌿', 'label' => 'Outdoor', 'desc' => 'Taman terbuka'],
                            ] as $area)
                            <button
                                @click="selectArea('{{ $area['value'] }}')"
                                :class="form.tableArea === '{{ $area['value'] }}'
                                    ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30'
                                    : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'"
                                class="p-4 rounded-xl border-2 text-left transition-all duration-200"
                            >
                                <div class="text-2xl mb-1">{{ $area['icon'] }}</div>
                                <div class="font-bold text-gray-900 dark:text-white text-sm">{{ $area['label'] }}</div>
                                <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $area['desc'] }}</div>
                            </button>
                            @endforeach
                        </div>

                        {{-- Pilih Meja (muncul setelah area dipilih) --}}
                        <div x-show="form.tableArea" x-transition class="mt-2">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Pilih Meja
                                    <span class="text-xs font-normal text-gray-400 ml-1">(untuk <span x-text="form.guests"></span> orang)</span>
                                </p>
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Tersedia</span>
                                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span> Dipesan</span>
                                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Penuh</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <template x-for="table in currentTables" :key="table.id">
                                    <button
                                        @click="selectTable(table)"
                                        :disabled="!isTableSelectable(table)"
                                        :class="{
                                            'border-primary-600 bg-primary-50 dark:bg-primary-900/30 ring-2 ring-primary-500': form.tableNumber === table.id,
                                            'border-emerald-300 dark:border-emerald-700 hover:border-primary-400 hover:bg-gray-50 dark:hover:bg-dark-700 cursor-pointer': isTableSelectable(table) && form.tableNumber !== table.id,
                                            'border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/20 opacity-60 cursor-not-allowed': table.status === 'occupied',
                                            'border-yellow-200 dark:border-yellow-900 bg-yellow-50 dark:bg-yellow-900/20 opacity-60 cursor-not-allowed': table.status === 'reserved',
                                            'border-gray-200 dark:border-dark-600 opacity-50 cursor-not-allowed': table.status === 'available' && table.capacity < form.guests
                                        }"
                                        class="p-3 rounded-xl border-2 text-left transition-all duration-200 relative"
                                    >
                                        <div class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full"
                                            :class="{
                                                'bg-emerald-500': table.status === 'available' && table.capacity >= form.guests,
                                                'bg-yellow-400': table.status === 'reserved',
                                                'bg-red-400': table.status === 'occupied',
                                                'bg-gray-300': table.status === 'available' && table.capacity < form.guests
                                            }">
                                        </div>
                                        <div class="text-base mb-0.5 font-bold text-gray-800 dark:text-white" x-text="table.label"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400" x-text="table.position"></div>
                                        <div class="flex items-center gap-1 mt-1">
                                            <span class="text-[10px] text-gray-400">👥</span>
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400" x-text="table.capacity + ' orang'"></span>
                                        </div>
                                        <div class="mt-1.5">
                                            <span x-show="table.status === 'occupied'"
                                                class="text-[10px] font-bold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-1.5 py-0.5 rounded-full">Penuh</span>
                                            <span x-show="table.status === 'reserved'"
                                                class="text-[10px] font-bold text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 px-1.5 py-0.5 rounded-full">Dipesan</span>
                                            <span x-show="table.status === 'available' && table.capacity < form.guests"
                                                class="text-[10px] font-bold text-gray-500 bg-gray-100 dark:bg-dark-600 px-1.5 py-0.5 rounded-full">Kapasitas kurang</span>
                                            <span x-show="table.status === 'available' && table.capacity >= form.guests && form.tableNumber !== table.id"
                                                class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-1.5 py-0.5 rounded-full">Tersedia</span>
                                            <span x-show="form.tableNumber === table.id"
                                                class="text-[10px] font-bold text-primary-600 dark:text-primary-400 bg-primary-100 dark:bg-primary-900/30 px-1.5 py-0.5 rounded-full">✓ Dipilih</span>
                                        </div>
                                    </button>
                                </template>
                            </div>
                            <div x-show="form.tableNumber" x-transition
                                class="mt-3 flex items-center gap-2 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-xl px-4 py-2.5">
                                <span class="text-primary-600 text-lg">🪑</span>
                                <p class="text-sm text-primary-700 dark:text-primary-300 font-medium">
                                    Meja dipilih: <strong x-text="getTableLabel(form.tableNumber)"></strong>
                                </p>
                            </div>
                            <div x-show="form.tableArea && currentTables.filter(t => isTableSelectable(t)).length === 0" x-transition
                                class="mt-3 flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl px-4 py-2.5">
                                <span class="text-red-500 text-lg">⚠️</span>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Tidak ada meja tersedia untuk <strong x-text="form.guests + ' orang'"></strong> di area ini. Coba kurangi jumlah tamu atau pilih area lain.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button
                        @click="nextStep()"
                        :disabled="!canProceed"
                        :class="!canProceed ? 'opacity-50 cursor-not-allowed' : ''"
                        class="btn btn-primary w-full"
                    >
                        Lanjutkan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </div>

                {{-- Step 2: Pilih Menu & Pembayaran --}}
                <div x-show="step === 2" class="card p-8 animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pilih Menu & Metode Pembayaran</h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Menu Pilihan</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pilih minimal 1 menu untuk reservasimu.</p>
                                </div>
                                <span class="text-sm text-primary-600 font-semibold" x-text="selectedItems.length + ' item dipilih'"></span>
                            </div>
                            <template x-for="menu in menus" :key="menu.id">
                                <div class="border border-gray-200 dark:border-dark-700 rounded-2xl p-4 flex items-center gap-4">
                                    <img :src="menu.image" alt="" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-900 dark:text-white" x-text="menu.name"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="menu.description || 'Tanpa deskripsi tambahan'"></div>
                                        <div class="text-sm font-bold text-primary-600 mt-2" x-text="formatPrice(menu.price)"></div>
                                    </div>
                                    <button @click="toggleMenu(menu)"
                                        :class="selectedItems.find(i => i.id === menu.id) ? 'bg-primary-600 text-white hover:bg-primary-700' : 'bg-white dark:bg-dark-700 border border-gray-200 dark:border-dark-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50'"
                                        class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                                        <span x-text="selectedItems.find(i => i.id === menu.id) ? 'Tambah +1' : 'Pilih'"></span>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="space-y-4">
                            <div class="border border-gray-200 dark:border-dark-700 rounded-2xl p-4 bg-gray-50 dark:bg-dark-700">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Ringkasan Pesanan</h3>
                                <template x-if="selectedItems.length === 0">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada menu dipilih.</p>
                                </template>
                                <template x-for="item in selectedItems" :key="item.id">
                                    <div class="flex items-center justify-between gap-3 py-2">
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="item.name"></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">x<span x-text="item.qty"></span></div>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="formatPrice(item.price * item.qty)"></div>
                                    </div>
                                </template>
                                <div class="border-t border-gray-200 dark:border-dark-600 pt-3 mt-3 flex items-center justify-between text-sm font-semibold text-gray-900 dark:text-white">
                                    <span>Total Menu</span>
                                    <span x-text="formatPrice(selectedMenuTotal)"></span>
                                </div>
                            </div>

                            <div class="border border-gray-200 dark:border-dark-700 rounded-2xl p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Metode Pembayaran</h3>
                                <div class="grid grid-cols-1 gap-3">
                                    <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                        :class="paymentMethod === 'cash' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                        <input type="radio" x-model="paymentMethod" value="cash" class="hidden">
                                        <div class="font-semibold text-gray-900 dark:text-white">Tunai</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bayar tunai saat kedatangan.</div>
                                    </label>
                                    <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                        :class="paymentMethod === 'qris' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                        <input type="radio" x-model="paymentMethod" value="qris" class="hidden">
                                        <div class="font-semibold text-gray-900 dark:text-white">QRIS</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bayar via DANA, OVO, atau GoPay.</div>
                                    </label>
                                    <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                        :class="paymentMethod === 'va' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                        <input type="radio" x-model="paymentMethod" value="va" class="hidden">
                                        <div class="font-semibold text-gray-900 dark:text-white">Virtual Account</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Transfer via bank virtual account.</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button @click="prevStep()" class="btn btn-secondary flex-1">Kembali</button>
                        <button @click="cancelReservation()" type="button" class="btn btn-outline text-red-600 border-red-200 dark:border-red-600 dark:text-red-400 flex-1">Batal</button>
                        <button @click="nextStep()" :disabled="!canProceed"
                            :class="!canProceed ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn btn-primary flex-1">
                            Lanjutkan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Step 3: Guest Details --}}
                <div x-show="step === 3" class="card p-8 animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Detail Tamu</h2>
                    <div class="space-y-4 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap *</label>
                                <input x-model="form.name" type="text" placeholder="Masukkan nama lengkap" class="input">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No. Telepon *</label>
                                <input x-model="form.phone" type="tel" placeholder="+62 812-xxxx-xxxx" class="input">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input x-model="form.email" type="email" placeholder="email@contoh.com" class="input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan Khusus</label>
                            <textarea x-model="form.notes" rows="3"
                                placeholder="Contoh: ada yang berulang tahun, alergi makanan tertentu, dll."
                                class="input resize-none"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button @click="prevStep()" class="btn btn-secondary flex-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                            </svg>
                            Kembali
                        </button>
                        <button @click="nextStep()" :disabled="!form.name || !form.phone"
                            :class="!form.name || !form.phone ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn btn-primary flex-1">
                            Lanjutkan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Step 4: Confirmation --}}
                <div x-show="step === 4" class="card p-8 animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Konfirmasi Reservasi</h2>
                    <div class="bg-gray-50 dark:bg-dark-700 rounded-xl p-6 mb-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">📅 Tanggal</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="form.date"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">🕐 Jam</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="form.time"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">👥 Jumlah Tamu</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="form.guests + ' orang'"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">🪑 Area</span>
                            <span class="font-semibold text-gray-900 dark:text-white capitalize" x-text="form.tableArea"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">🪑 Meja</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="getTableLabel(form.tableNumber)"></span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-dark-600 pt-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">👤 Nama</span>
                                <span class="font-semibold text-gray-900 dark:text-white" x-text="form.name"></span>
                            </div>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">📞 Telepon</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="form.phone"></span>
                        </div>
                    </div>

                    <div x-show="message" x-transition
                        :class="messageType === 'error'
                            ? 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-700/50 text-red-700 dark:text-red-300'
                            : 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-700/50 text-green-700 dark:text-green-300'"
                        class="mb-6 p-4 rounded-lg border">
                        <div class="flex items-start gap-3">
                            <span class="text-xl" x-text="messageType === 'error' ? '❌' : '✅'"></span>
                            <span x-text="message"></span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button @click="prevStep()" :disabled="loading" class="btn btn-secondary flex-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                            </svg>
                            Kembali
                        </button>
                        <button @click="submit()" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''" class="btn btn-primary flex-1 flex items-center justify-center gap-2">
                            <span x-show="!loading">✅ Konfirmasi Reservasi</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION: PROMO                                               --}}
{{-- ============================================================ --}}
<section id="promo" class="section bg-gray-50 dark:bg-dark-900">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="badge badge-warning mb-3">🎁 Penawaran Spesial</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Promo & <span class="gradient-text">Diskon</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Hemat lebih banyak dengan promo eksklusif kami setiap hari</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @php
            $promos = [
                ['gradient'=>'from-primary-600 to-orange-500','icon'=>'🍔','title'=>'Diskon 30% Makanan','desc'=>'Berlaku setiap hari Senin untuk semua menu makanan','code'=>'SENIN30','expiry'=>'Setiap Senin','badge'=>'Mingguan'],
                ['gradient'=>'from-purple-600 to-pink-500','icon'=>'🥤','title'=>'Buy 1 Get 1 Minuman','desc'=>'Setiap weekend pukul 14.00-17.00 WIB','code'=>'WEEKEND2X','expiry'=>'Sabtu & Minggu','badge'=>'Weekend'],
                ['gradient'=>'from-green-600 to-teal-500','icon'=>'🎂','title'=>'Free Dessert','desc'=>'Gratis dessert untuk pembelian min. Rp 100.000','code'=>'FREEDESSERT','expiry'=>'Berlaku terus','badge'=>'Permanen'],
                ['gradient'=>'from-blue-600 to-cyan-500','icon'=>'👤','title'=>'Diskon 15% New User','desc'=>'Khusus untuk pelanggan baru yang pertama kali order','code'=>'NEWUSER','expiry'=>'Sekali pakai','badge'=>'New User'],
                ['gradient'=>'from-red-600 to-rose-500','icon'=>'🎉','title'=>'Potongan Rp 20.000','desc'=>'Potongan langsung untuk pembelian min. Rp 75.000','code'=>'GRATIS20','expiry'=>'Berlaku terus','badge'=>'Cashback'],
                ['gradient'=>'from-yellow-500 to-amber-500','icon'=>'⭐','title'=>'Member Diskon 10%','desc'=>'Diskon 10% untuk semua member terdaftar','code'=>'HEMAT10','expiry'=>'Berlaku terus','badge'=>'Member'],
            ];
            @endphp
            @foreach($promos as $promo)
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $promo['gradient'] }} p-6 text-white group hover:scale-105 transition-transform duration-300" x-data="{copied:false}">
                <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full"></div>
                <div class="absolute -right-4 bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-4xl">{{ $promo['icon'] }}</span>
                        <span class="badge bg-white/20 text-white text-xs">{{ $promo['badge'] }}</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">{{ $promo['title'] }}</h3>
                    <p class="text-white/80 text-sm mb-4">{{ $promo['desc'] }}</p>
                    <div class="flex items-center gap-2">
                        <div class="bg-white/20 rounded-lg px-3 py-2 flex-1">
                            <span class="font-mono font-bold text-sm">{{ $promo['code'] }}</span>
                        </div>
                        <button @click="navigator.clipboard.writeText('{{ $promo['code'] }}');copied=true;setTimeout(()=>copied=false,2000)"
                            class="bg-white/20 hover:bg-white/30 rounded-lg px-3 py-2 text-sm font-medium transition-all">
                            <span x-show="!copied">Salin</span>
                            <span x-show="copied">✓ Disalin!</span>
                        </button>
                    </div>
                    <div class="mt-3 text-white/60 text-xs flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $promo['expiry'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Loyalty --}}
        <div class="card p-8 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-800 dark:to-dark-700 text-white">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="badge bg-yellow-500/20 text-yellow-400 mb-4">👑 Loyalty Program</span>
                    <h3 class="font-display text-3xl font-bold mb-4">Kumpulkan Poin, Dapatkan Hadiah!</h3>
                    <p class="text-gray-400 mb-6">Setiap pembelian Rp 10.000 = 1 poin. Tukarkan poin kamu dengan diskon, menu gratis, dan hadiah menarik lainnya.</p>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        @foreach([['points'=>'100','reward'=>'Diskon 5%'],['points'=>'250','reward'=>'Free Minuman'],['points'=>'500','reward'=>'Free Makanan']] as $tier)
                        <div class="bg-white/10 rounded-xl p-3 text-center">
                            <div class="text-yellow-400 font-bold text-lg">{{ $tier['points'] }}</div>
                            <div class="text-xs text-gray-400">poin</div>
                            <div class="text-white text-xs font-medium mt-1">{{ $tier['reward'] }}</div>
                        </div>
                        @endforeach
                    </div>
                    <a href="#menu" class="nav-scroll btn bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold">Mulai Kumpulkan Poin</a>
                </div>
                <div class="text-center">
                    <div class="text-8xl animate-float">🏆</div>
                    <div class="mt-4 text-gray-400 text-sm">Bergabung dengan 10.000+ member aktif</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- SECTION: TENTANG                                             --}}
{{-- ============================================================ --}}
<section id="tentang" class="section bg-white dark:bg-dark-800">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="badge badge-primary mb-3">🏠 Tentang Kami</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Cerita <span class="gradient-text">Kami</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Dari dapur keluarga ke restoran digital modern</p>
        </div>

        {{-- Story --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <div x-data x-intersect="$el.classList.add('animate-slide-up')">
                <span class="badge badge-primary mb-4">📖 Kisah Kami</span>
                <h3 class="font-display text-4xl font-bold text-gray-900 dark:text-white mb-6">Berawal dari Cinta<br>terhadap Kuliner</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                    Restoran NUSANTARA didirikan pada tahun 2019 dengan satu misi sederhana: menyajikan cita rasa autentik masakan Nusantara dengan sentuhan modern yang memudahkan semua orang menikmatinya.
                </p>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                    Dimulai dari warung kecil di sudut kota, kini kami telah berkembang menjadi restoran digital yang melayani ribuan pelanggan setiap harinya.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([['value'=>'2019','label'=>'Tahun Berdiri'],['value'=>'10K+','label'=>'Pelanggan Puas'],['value'=>'500+','label'=>'Menu Tersedia'],['value'=>'4.9★','label'=>'Rating Google']] as $s)
                    <div class="bg-gray-50 dark:bg-dark-700 rounded-xl p-4">
                        <div class="text-2xl font-bold text-primary-600">{{ $s['value'] }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ $s['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative" x-data x-intersect="$el.classList.add('animate-fade-in')">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=500&fit=crop" alt="Restaurant" class="rounded-2xl shadow-2xl w-full">
                <div class="absolute -bottom-6 -left-6 bg-white dark:bg-dark-800 rounded-2xl p-4 shadow-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-2xl">👨‍🍳</div>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-white text-sm">Chef Berpengalaman</div>
                            <div class="text-gray-500 dark:text-gray-400 text-xs">15+ tahun pengalaman</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Values --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            @foreach([['icon'=>'❤️','title'=>'Passion','desc'=>'Kami memasak dengan penuh cinta dan dedikasi untuk setiap hidangan yang kami sajikan'],['icon'=>'🌿','title'=>'Kualitas','desc'=>'Bahan-bahan segar pilihan terbaik dipilih setiap hari untuk memastikan kualitas terjaga'],['icon'=>'🤝','title'=>'Kepercayaan','desc'=>'Membangun kepercayaan pelanggan adalah prioritas utama kami dalam setiap pelayanan']] as $v)
            <div class="card p-6 text-center card-hover" x-data x-intersect="$el.classList.add('animate-slide-up')">
                <div class="text-5xl mb-4">{{ $v['icon'] }}</div>
                <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-3">{{ $v['title'] }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Team --}}
        <div class="text-center mb-10">
            <h3 class="font-display text-3xl font-bold text-gray-900 dark:text-white mb-2">Tim <span class="gradient-text">Kami</span></h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([['name'=>'Pak Budi','role'=>'Head Chef','emoji'=>'👨‍🍳','color'=>'from-orange-400 to-red-500'],['name'=>'Bu Sari','role'=>'Pastry Chef','emoji'=>'👩‍🍳','color'=>'from-pink-400 to-purple-500'],['name'=>'Mas Andi','role'=>'Barista','emoji'=>'☕','color'=>'from-amber-400 to-orange-500'],['name'=>'Mbak Rina','role'=>'Manager','emoji'=>'👩‍💼','color'=>'from-blue-400 to-cyan-500']] as $m)
            <div class="card p-6 text-center card-hover" x-data x-intersect="$el.classList.add('animate-slide-up')">
                <div class="w-20 h-20 bg-gradient-to-br {{ $m['color'] }} rounded-2xl flex items-center justify-center text-4xl mx-auto mb-4 shadow-lg">{{ $m['emoji'] }}</div>
                <h4 class="font-bold text-gray-900 dark:text-white">{{ $m['name'] }}</h4>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $m['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- SECTION: KONTAK                                              --}}
{{-- ============================================================ --}}
<section id="kontak" class="section bg-gray-50 dark:bg-dark-900">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="badge badge-primary mb-3">📞 Hubungi Kami</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Ada <span class="gradient-text">Pertanyaan?</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Kami siap membantu kamu 7 hari seminggu</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Info --}}
            <div class="space-y-4">
                @foreach([['icon'=>'📍','title'=>'Alamat','lines'=>['Jl. Kuliner Nusantara No. 88','Jakarta Selatan, 12345']],['icon'=>'📞','title'=>'Telepon','lines'=>['+62 812-3456-7890','+62 21-1234-5678']],['icon'=>'✉️','title'=>'Email','lines'=>['hello@warungnusantara.id','support@warungnusantara.id']],['icon'=>'🕐','title'=>'Jam Operasional','lines'=>['Senin – Jumat: 10.00 – 22.00','Sabtu – Minggu: 09.00 – 23.00']]] as $info)
                <div class="card p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">{{ $info['icon'] }}</div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">{{ $info['title'] }}</h3>
                        @foreach($info['lines'] as $line)
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $line }}</p>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Form --}}
            <div class="lg:col-span-2">
                <div class="card p-8" x-data="{submitted:false,form:{name:'',email:'',subject:'',message:''}}">
                    <div x-show="submitted" class="text-center py-12 animate-fade-in">
                        <div class="text-6xl mb-4">✅</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pesan Terkirim!</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Kami akan membalas dalam 1x24 jam</p>
                        <button @click="submitted=false;form={name:'',email:'',subject:'',message:''}" class="btn btn-primary">Kirim Pesan Lain</button>
                    </div>
                    <div x-show="!submitted">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Kirim Pesan</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap *</label>
                                    <input x-model="form.name" type="text" placeholder="Nama kamu" class="input">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                                    <input x-model="form.email" type="email" placeholder="email@contoh.com" class="input">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subjek</label>
                                <select x-model="form.subject" class="input">
                                    <option value="">Pilih subjek...</option>
                                    <option>Pertanyaan Menu</option>
                                    <option>Reservasi</option>
                                    <option>Keluhan</option>
                                    <option>Kerjasama</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pesan *</label>
                                <textarea x-model="form.message" rows="5" placeholder="Tulis pesanmu di sini..." class="input resize-none"></textarea>
                            </div>
                            <button @click="if(form.name&&form.email&&form.message)submitted=true"
                                :disabled="!form.name||!form.email||!form.message"
                                :class="!form.name||!form.email||!form.message?'opacity-50 cursor-not-allowed':''"
                                class="btn btn-primary w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Kirim Pesan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Map --}}
        <div class="mt-10 card overflow-hidden">
            <div class="bg-gray-200 dark:bg-dark-700 h-64 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-5xl mb-3">🗺️</div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Google Maps</p>
                    <p class="text-gray-500 dark:text-gray-500 text-sm">Jl. Kuliner Nusantara No. 88, Jakarta Selatan</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- SECTION: GALERI                                              --}}
{{-- ============================================================ --}}
<section id="galeri" class="section bg-white dark:bg-dark-800" x-data="galleryLightbox()">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="badge badge-primary mb-3">📸 Galeri</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Momen <span class="gradient-text">Bersama</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                Kenangan indah pelanggan kami yang menikmati hidangan bersama orang tersayang
            </p>
        </div>

        @php
        $galleryImages = [
            ['src' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=400&fit=crop', 'caption' => 'Suasana makan malam romantis', 'span' => 'col-span-2 row-span-2'],
            ['src' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=300&fit=crop', 'caption' => 'Interior restoran modern', 'span' => ''],
            ['src' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=400&h=300&fit=crop', 'caption' => 'Keluarga bahagia bersantap', 'span' => ''],
            ['src' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=400&h=300&fit=crop', 'caption' => 'Momen spesial bersama pasangan', 'span' => ''],
            ['src' => 'https://images.unsplash.com/photo-1544148103-0773bf10d330?w=400&h=300&fit=crop', 'caption' => 'Kumpul bersama teman', 'span' => ''],
            ['src' => 'https://images.unsplash.com/photo-1424847651672-bf20a4b0982b?w=400&h=300&fit=crop', 'caption' => 'Perayaan ulang tahun', 'span' => ''],
            ['src' => 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=400&h=300&fit=crop', 'caption' => 'Makan siang bisnis', 'span' => ''],
            ['src' => 'https://images.unsplash.com/photo-1529543544282-ea669407fca3?w=400&h=300&fit=crop', 'caption' => 'Pelanggan setia kami', 'span' => ''],
            ['src' => 'https://images.unsplash.com/photo-1567521464027-f127ff144326?w=400&h=300&fit=crop', 'caption' => 'Hidangan spesial chef', 'span' => ''],
        ];
        $imgList = array_column($galleryImages, 'src');
        @endphp

        {{-- Masonry Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 auto-rows-[180px]">
            @foreach($galleryImages as $i => $img)
            <div
                class="relative overflow-hidden rounded-2xl cursor-pointer group {{ $img['span'] }}"
                @click="openAt({{ json_encode($imgList) }}, {{ $i }})"
                x-data x-intersect="$el.classList.add('animate-fade-in')"
            >
                <img
                    src="{{ $img['src'] }}"
                    alt="{{ $img['caption'] }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    loading="lazy"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                    <div>
                        <p class="text-white text-sm font-medium">{{ $img['caption'] }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                            <span class="text-white/70 text-xs">Klik untuk perbesar</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Lightbox --}}
        <div x-show="open" x-cloak
            class="fixed inset-0 z-[90] flex items-center justify-center bg-black/95"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        >
            {{-- Close --}}
            <button @click="close()" class="absolute top-4 right-4 z-10 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            {{-- Prev --}}
            <button @click="prev()" class="absolute left-4 z-10 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            {{-- Image --}}
            <div class="max-w-4xl max-h-[85vh] mx-16">
                <img :src="images[current]" :alt="`Foto ${current+1}`" class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl">
                <p class="text-white/60 text-center text-sm mt-3" x-text="`${current+1} / ${images.length}`"></p>
            </div>
            {{-- Next --}}
            <button @click="next()" class="absolute right-4 z-10 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        {{-- Customer Photos --}}
        <div class="mt-16">
            <h3 class="font-display text-2xl font-bold text-gray-900 dark:text-white text-center mb-8">
                📸 Foto Pelanggan Kami
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                $customers = [
                    ['img' => 'https://images.unsplash.com/photo-1529543544282-ea669407fca3?w=200&h=200&fit=crop&crop=face', 'name' => 'Budi & Keluarga', 'rating' => 5],
                    ['img' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=200&h=200&fit=crop&crop=face', 'name' => 'Siti & Teman', 'rating' => 5],
                    ['img' => 'https://images.unsplash.com/photo-1567521464027-f127ff144326?w=200&h=200&fit=crop&crop=face', 'name' => 'Ahmad & Pasangan', 'rating' => 5],
                    ['img' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=200&h=200&fit=crop&crop=face', 'name' => 'Rina & Sahabat', 'rating' => 5],
                    ['img' => 'https://images.unsplash.com/photo-1544148103-0773bf10d330?w=200&h=200&fit=crop&crop=face', 'name' => 'Doni & Grup', 'rating' => 5],
                    ['img' => 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=200&h=200&fit=crop&crop=face', 'name' => 'Maya & Rekan', 'rating' => 5],
                ];
                @endphp
                @foreach($customers as $c)
                <div class="text-center group" x-data x-intersect="$el.classList.add('animate-slide-up')">
                    <div class="relative w-20 h-20 mx-auto mb-2">
                        <img src="{{ $c['img'] }}" alt="{{ $c['name'] }}" class="w-full h-full rounded-full object-cover border-3 border-white dark:border-dark-700 shadow-lg group-hover:scale-110 transition-transform duration-300" loading="lazy">
                        <div class="absolute -bottom-1 -right-1 bg-yellow-400 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold text-gray-900">★</div>
                    </div>
                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $c['name'] }}</p>
                    <div class="flex justify-center gap-0.5 mt-1">
                        @for($s=0;$s<$c['rating'];$s++)<span class="text-yellow-400 text-xs">★</span>@endfor
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- SECTION: FASILITAS                                           --}}
{{-- ============================================================ --}}
<section id="fasilitas" class="section bg-gray-50 dark:bg-dark-900">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="badge badge-primary mb-3">🏢 Fasilitas</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Fasilitas <span class="gradient-text">Lengkap</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                Nikmati pengalaman bersantap terbaik dengan fasilitas modern kami
            </p>
        </div>

        {{-- Facility Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @php
            $facilities = [
                ['icon'=>'❄️','title'=>'Ruang Ber-AC','desc'=>'Ruangan indoor nyaman dengan pendingin udara modern untuk kenyamanan maksimal','img'=>'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&h=300&fit=crop','tag'=>'Indoor'],
                ['icon'=>'🌿','title'=>'Taman Outdoor','desc'=>'Area outdoor asri dengan taman hijau, cocok untuk bersantai sambil menikmati udara segar','img'=>'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500&h=300&fit=crop','tag'=>'Outdoor'],
                ['icon'=>'📶','title'=>'Free WiFi','desc'=>'Koneksi internet cepat tersedia di seluruh area restoran, kecepatan hingga 100 Mbps','img'=>'https://images.unsplash.com/photo-1544148103-0773bf10d330?w=500&h=300&fit=crop','tag'=>'Teknologi'],
                ['icon'=>'🅿️','title'=>'Parkir Luas','desc'=>'Area parkir luas dan aman untuk kendaraan roda dua maupun roda empat','img'=>'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=500&h=300&fit=crop','tag'=>'Parkir'],
                ['icon'=>'🎵','title'=>'Live Music','desc'=>'Hiburan live music setiap Jumat & Sabtu malam untuk menemani makan malam kamu','img'=>'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=500&h=300&fit=crop','tag'=>'Hiburan'],
                ['icon'=>'👶','title'=>'Area Anak','desc'=>'Playground khusus anak-anak agar si kecil bisa bermain dengan aman dan menyenangkan','img'=>'https://images.unsplash.com/photo-1526634332515-d56c5fd16991?w=500&h=300&fit=crop','tag'=>'Keluarga'],
                ['icon'=>'🎂','title'=>'Private Room','desc'=>'Ruang privat eksklusif untuk acara ulang tahun, anniversary, atau pertemuan bisnis','img'=>'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=500&h=300&fit=crop','tag'=>'VIP'],
                ['icon'=>'♿','title'=>'Akses Difabel','desc'=>'Fasilitas ramah difabel dengan ramp, toilet khusus, dan area parkir prioritas','img'=>'https://images.unsplash.com/photo-1529543544282-ea669407fca3?w=500&h=300&fit=crop','tag'=>'Inklusif'],
                ['icon'=>'🔒','title'=>'CCTV 24 Jam','desc'=>'Keamanan terjamin dengan sistem CCTV 24 jam dan petugas keamanan berpengalaman','img'=>'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=500&h=300&fit=crop','tag'=>'Keamanan'],
            ];
            @endphp

            @foreach($facilities as $f)
            <div class="card card-hover group overflow-hidden" x-data="{expanded: false}" x-intersect="$el.classList.add('animate-slide-up')">
                {{-- Image --}}
                <div class="relative h-44 overflow-hidden">
                    <img src="{{ $f['img'] }}" alt="{{ $f['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute top-3 right-3">
                        <span class="badge bg-white/20 backdrop-blur-sm text-white text-xs border border-white/30">{{ $f['tag'] }}</span>
                    </div>
                    <div class="absolute bottom-3 left-3 flex items-center gap-2">
                        <span class="text-2xl">{{ $f['icon'] }}</span>
                        <h3 class="text-white font-bold">{{ $f['title'] }}</h3>
                    </div>
                </div>
                {{-- Content --}}
                <div class="p-4">
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Capacity Info --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['icon'=>'🪑','value'=>'200','label'=>'Kapasitas Kursi'],
                ['icon'=>'🏠','value'=>'3','label'=>'Ruang Privat'],
                ['icon'=>'🅿️','value'=>'50+','label'=>'Slot Parkir'],
                ['icon'=>'⏰','value'=>'12 Jam','label'=>'Jam Operasional'],
            ] as $cap)
            <div class="card p-5 text-center" x-data x-intersect="$el.classList.add('animate-slide-up')">
                <div class="text-3xl mb-2">{{ $cap['icon'] }}</div>
                <div class="text-2xl font-bold text-primary-600 mb-1">{{ $cap['value'] }}</div>
                <div class="text-gray-500 dark:text-gray-400 text-sm">{{ $cap['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- SECTION: REKOMENDASI MENU                                    --}}
{{-- ============================================================ --}}
<section class="section bg-white dark:bg-dark-800" x-data="menuFilter({{ $menus->toJson() }})">
    <div class="container-custom">
        <div class="text-center mb-10">
            <span class="badge badge-warning mb-3">💡 Rekomendasi</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Menu yang <span class="gradient-text">Cocok Untukmu</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                Berdasarkan kategori favoritmu: <strong class="text-primary-600 capitalize" x-text="favoriteCategory"></strong>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <template x-for="menu in recommendations" :key="menu.id">
                <div class="card card-hover group relative overflow-hidden" x-intersect="$el.classList.add('animate-slide-up')">
                    {{-- Recommended badge --}}
                    <div class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-bold text-center py-1">
                        ⭐ Direkomendasikan Untukmu
                    </div>
                    <div class="relative overflow-hidden h-48 mt-6">
                        <img :src="menu.image" :alt="menu.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button @click="addToCart(menu)" class="btn bg-white text-gray-900 hover:bg-primary-600 hover:text-white text-sm py-2 px-4 transform scale-90 group-hover:scale-100 transition-all duration-300">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1 truncate" x-text="menu.name"></h3>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mb-3 line-clamp-2" x-text="menu.description"></p>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-primary-600 font-bold" x-text="formatPrice(menu.price)"></div>
                                <div x-show="menu.originalPrice" class="text-gray-400 text-xs line-through" x-text="menu.originalPrice ? formatPrice(menu.originalPrice) : ''"></div>
                            </div>
                            <button @click="addToCart(menu)" class="w-9 h-9 bg-primary-600 hover:bg-primary-700 text-white rounded-lg flex items-center justify-center transition-all hover:scale-110 shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>

@endsection
