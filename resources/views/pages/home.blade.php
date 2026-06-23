
@extends('layouts.app')
@section('title', 'Rumah Makan Saung Bambu — Smart Digital Restaurant')
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
                <a href="/reservasi" class="btn btn-outline border-white text-white hover:bg-white hover:text-gray-900 text-base px-8 py-4">
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
<div class="bg-primary-600 py-5">
    <div class="container-custom">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div><div class="font-bold text-sm">Order Cepat</div><div class="text-primary-200 text-xs">Proses 15–30 menit</div></div>
            </div>
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div><div class="font-bold text-sm">Promo Harian</div><div class="text-primary-200 text-xs">Diskon hingga 50%</div></div>
            </div>
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <div><div class="font-bold text-sm">Kualitas Premium</div><div class="text-primary-200 text-xs">Bahan pilihan terbaik</div></div>
            </div>
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div><div class="font-bold text-sm">Order Online</div><div class="text-primary-200 text-xs">Mudah & praktis</div></div>
            </div>
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
            <span class="badge badge-primary mb-3">Menu Kami</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Pilih Menu <span class="gradient-text">Favoritmu</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Dari makanan berat hingga dessert manis, semua ada di sini</p>
        </div>

        {{-- ===== MENU POPULER (selalu tampil di atas) ===== --}}
        <div x-show="!search && activeCategory === 'all' && priceRange === 'all' && !showOnlyPromo" class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c0 0-3 3.5-3 7a3 3 0 006 0c0-3.5-3-7-3-7zm-5 9c0 0-2 2-2 4a2 2 0 004 0c0-2-2-4-2-4zm10 0c0 0-2 2-2 4a2 2 0 004 0c0-2-2-4-2-4z"/></svg>
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
                                <span x-show="menu.label==='best-seller'" class="text-[10px] bg-primary-600 text-white px-1.5 py-0.5 rounded-full font-bold">Hot</span>
                                <span x-show="menu.label==='popular'" class="text-[10px] bg-blue-600 text-white px-1.5 py-0.5 rounded-full font-bold">Top</span>
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
                        placeholder="Cari menu... contoh: ayam, pedas, kopi"
                        class="input pl-12 pr-10 text-sm w-full">
                    <button x-show="search" @click="search=''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-200 dark:bg-dark-600 hover:bg-gray-300 rounded-full flex items-center justify-center transition-all">
                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <select x-model="sortBy" class="input sm:w-44 text-sm flex-shrink-0">
                    <option value="popular">Terpopuler</option>
                    <option value="rating">Rating Tertinggi</option>
                    <option value="price-low">Harga Terendah</option>
                    <option value="price-high">Harga Tertinggi</option>
                    <option value="new">Terbaru</option>
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
            <div class="flex gap-2 overflow-x-auto scrollbar-thin pb-2 mb-4">
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
                    &times; Reset semua filter
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
                            <span x-show="menu.label==='best-seller'" class="badge bg-primary-600 text-white text-xs">Best Seller</span>
                            <span x-show="menu.label==='popular'" class="badge bg-blue-600 text-white text-xs">Populer</span>
                            <span x-show="menu.isNew" class="badge bg-green-600 text-white text-xs">Baru</span>
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
                <div class="w-16 h-16 bg-gray-100 dark:bg-dark-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Menu tidak ditemukan</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-2">
                    Tidak ada menu yang cocok dengan
                    <span x-show="search">"<span class="text-primary-600 font-semibold" x-text="search"></span>"</span>
                    <span x-show="activeCategory !== 'all'"> di kategori <span class="font-semibold" x-text="categoryLabel(activeCategory)"></span></span>
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
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span x-text="selectedMenu.rating"></span> • <span x-text="selectedMenu.reviews + ' ulasan'"></span>
                                </div>
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl bg-primary-50 text-sm text-primary-600">
                                    <span x-text="formatPrice(selectedMenu.price)"></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-6 text-sm text-gray-600 dark:text-gray-300">
                                <div class="p-4 rounded-3xl bg-gray-50 dark:bg-dark-700">
                                    <div class="font-semibold">Kategori</div>
                                    <div x-text="categoryLabel(selectedMenu.category)"></div>
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
{{-- SECTION: PROMO                                               --}}
{{-- ============================================================ --}}

<section id="promo" class="section bg-gray-50 dark:bg-dark-900">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="badge badge-warning mb-3">Penawaran Spesial</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Promo & <span class="gradient-text">Diskon</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Hemat lebih banyak dengan promo eksklusif kami setiap hari</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @php
            $promos = [
                ['gradient'=>'from-primary-600 to-orange-500','title'=>'Diskon 30% Makanan','desc'=>'Berlaku setiap hari Senin untuk semua menu makanan','code'=>'SENIN30','expiry'=>'Setiap Senin','badge'=>'Mingguan','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                ['gradient'=>'from-purple-600 to-pink-500','title'=>'Buy 1 Get 1 Minuman','desc'=>'Setiap weekend pukul 14.00-17.00 WIB','code'=>'WEEKEND2X','expiry'=>'Sabtu & Minggu','badge'=>'Weekend','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['gradient'=>'from-green-600 to-teal-500','title'=>'Free Dessert','desc'=>'Gratis dessert untuk pembelian min. Rp 100.000','code'=>'FREEDESSERT','expiry'=>'Berlaku terus','badge'=>'Permanen','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>'],
                ['gradient'=>'from-blue-600 to-cyan-500','title'=>'Diskon 15% New User','desc'=>'Khusus untuk pelanggan baru yang pertama kali order','code'=>'NEWUSER','expiry'=>'Sekali pakai','badge'=>'New User','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
                ['gradient'=>'from-red-600 to-rose-500','title'=>'Potongan Rp 20.000','desc'=>'Potongan langsung untuk pembelian min. Rp 75.000','code'=>'GRATIS20','expiry'=>'Berlaku terus','badge'=>'Cashback','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['gradient'=>'from-yellow-500 to-amber-500','title'=>'Member Diskon 10%','desc'=>'Diskon 10% untuk semua member terdaftar','code'=>'HEMAT10','expiry'=>'Berlaku terus','badge'=>'Member','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
            ];
            @endphp
            @foreach($promos as $promo)
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $promo['gradient'] }} p-6 text-white group hover:scale-105 transition-transform duration-300" x-data="{copied:false}">
                <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full"></div>
                <div class="absolute -right-4 bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $promo['svg'] !!}</svg>
                        </div>
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

        {{-- Loyalty removed as per request --}}
    </div>
</section>

{{-- ============================================================ --}}
{{-- SECTION: TENTANG                                             --}}
{{-- ============================================================ --}}
<section id="tentang" class="section bg-white dark:bg-dark-800">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="badge badge-primary mb-3">Tentang Kami</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Cerita <span class="gradient-text">Kami</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Dari dapur keluarga ke rumah makan digital modern</p>
        </div>

        {{-- Story --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <div x-data x-intersect="$el.classList.add('animate-slide-up')">
                <span class="badge badge-primary mb-4">Kisah Kami</span>
                <h3 class="font-display text-4xl font-bold text-gray-900 dark:text-white mb-6">Berawal dari Cinta<br>terhadap Kuliner</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                    Rumah Makan Saung Bambu didirikan pada tahun 2016 dengan satu misi sederhana: menyajikan cita rasa autentik masakan Nusantara dengan sentuhan modern yang memudahkan semua orang menikmatinya.
                </p>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                    Dimulai dari warung kecil di sudut kota, kini kami telah berkembang menjadi rumah makan digital yang melayani ribuan pelanggan setiap harinya.
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
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
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
            @foreach([
                ['svgpath'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z','title'=>'Passion','desc'=>'Kami memasak dengan penuh cinta dan dedikasi untuk setiap hidangan yang kami sajikan'],
                ['svgpath'=>'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z','title'=>'Kualitas','desc'=>'Bahan-bahan segar pilihan terbaik dipilih setiap hari untuk memastikan kualitas terjaga'],
                ['svgpath'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','title'=>'Kepercayaan','desc'=>'Membangun kepercayaan pelanggan adalah prioritas utama kami dalam setiap pelayanan'],
            ] as $v)
            <div class="card p-6 text-center card-hover" x-data x-intersect="$el.classList.add('animate-slide-up')">
                <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $v['svgpath'] }}"/></svg>
                </div>
                <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-3">{{ $v['title'] }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $v['desc'] }}</p>
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
            <span class="badge badge-primary mb-3">Hubungi Kami</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Ada <span class="gradient-text">Pertanyaan?</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Kami siap membantu kamu 7 hari seminggu</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Info --}}
            <div class="space-y-4">
                @php
                $contactInfos = [
                    ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>','title'=>'Alamat','lines'=>['Jl. Setia Mekar No.3, Setiamekar, Kec. Tambun Sel., Kabupaten Bekasi, Jawa Barat 17510','Kec. Tambun Sel., Kabupaten Bekasi, Jawa Barat 17510']],
                    ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>','title'=>'Telepon','lines'=>['+62  0813-5000-0861','+62  0813-5000-0861']],
                    ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>','title'=>'Email','lines'=>['hello@rmsaungbambu.id','support@rmsaungbambu.id']],
                    ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>','title'=>'Jam Operasional','lines'=>['Senin – Jumat: 10.00 – 20.00','Sabtu – Minggu: 10.00 – 21.00']],
                ];
                @endphp
                @foreach($contactInfos as $info)
                <div class="card p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $info['svg'] !!}</svg>
                    </div>
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
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
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
                    <div class="w-14 h-14 bg-gray-300 dark:bg-dark-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
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
            <span class="badge badge-primary mb-3">Galeri</span>
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
                Foto Pelanggan Kami
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
            <span class="badge badge-primary mb-3">Fasilitas</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Fasilitas <span class="gradient-text">Lengkap</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                Nikmati pengalaman bersantap terbaik dengan fasilitas modern kami
            </p>
        </div>

        {{-- Facility Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($facilities as $f)
            <div class="card card-hover group overflow-hidden" x-data="{expanded: false}" x-intersect="$el.classList.add('animate-slide-up')">
                {{-- Image --}}
                <div class="relative h-44 overflow-hidden">
                    <img src="{{ $f->image_src }}" alt="{{ $f->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    @if($f->tag)
                    <div class="absolute top-3 right-3">
                        <span class="badge bg-white/20 backdrop-blur-sm text-white text-xs border border-white/30">{{ $f->tag }}</span>
                    </div>
                    @endif
                    <div class="absolute bottom-3 left-3 flex items-center gap-2">
                        <span class="text-white flex items-center justify-center">
                            @include('components.facility-icon', ['icon' => $f->icon])
                        </span>
                        <h3 class="text-white font-bold">{{ $f->title }}</h3>
                    </div>
                </div>
                {{-- Content --}}
                <div class="p-4">
                    <p class="text-gray-650 dark:text-gray-400 text-sm leading-relaxed">{{ $f->description }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Capacity Info --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>','value'=>'200','label'=>'Kapasitas Kursi'],
                ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>','value'=>'3','label'=>'Ruang Privat'],
                ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>','value'=>'50+','label'=>'Slot Parkir'],
                ['svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>','value'=>'12 Jam','label'=>'Jam Operasional'],
            ] as $cap)
            <div class="card p-5 text-center" x-data x-intersect="$el.classList.add('animate-slide-up')">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $cap['svg'] !!}</svg>
                </div>
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
            <span class="badge badge-warning mb-3">Rekomendasi</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Menu yang <span class="gradient-text">Cocok Untukmu</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                Berdasarkan kategori favoritmu: <strong class="text-primary-600" x-text="categoryLabel(favoriteCategory)"></strong>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <template x-for="menu in recommendations" :key="menu.id">
                <div class="card card-hover group relative overflow-hidden" x-intersect="$el.classList.add('animate-slide-up')">
                    {{-- Recommended badge --}}
                    <div class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-bold text-center py-1 flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Direkomendasikan Untukmu
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
