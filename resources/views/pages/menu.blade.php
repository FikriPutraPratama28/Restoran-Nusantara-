@extends('layouts.app')

@section('title', 'Menu — Warung Nusantara')

@section('content')

{{-- Page Header --}}
<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-primary mb-3">🍽️ Menu Kami</span>
        <h1 class="font-display text-4xl md:text-6xl font-bold text-white mb-4">
            Pilih Menu <span class="gradient-text">Favoritmu</span>
        </h1>
        <p class="text-gray-400 max-w-xl mx-auto">
            Dari makanan berat hingga dessert manis, semua ada di sini
        </p>
    </div>
</div>

{{-- Menu Section --}}
<section class="section bg-gray-50 dark:bg-dark-900" x-data="menuFilter({{ $menus->toJson() }})">

    <div class="container-custom">

        {{-- Menu Populer --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                <div>
                    <span class="badge badge-primary mb-2">⭐ Menu Populer</span>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pilihan favorit pelanggan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Temukan cepat menu populer yang sering dibeli dan disukai banyak orang.</p>
                </div>
                <button @click="sortBy='popular'; activeCategory='all'; priceRange='all'; search=''"
                    class="btn btn-ghost text-primary-600 hover:bg-primary-100 dark:hover:bg-dark-700 text-sm">
                    Lihat semua menu
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <template x-for="menu in popularMenus" :key="'pop-'+menu.id">
                    <div @click="openDetail(menu)"
                        :class="lastAddedId === menu.id ? 'ring-2 ring-primary-400/30 scale-[1.01]' : ''"
                        class="card border border-gray-200 dark:border-dark-700 p-4 bg-white dark:bg-dark-800 rounded-3xl hover:shadow-lg transition-all duration-200 relative overflow-hidden">
                        <div class="flex items-center gap-3">
                            <img :src="menu.image" :alt="menu.name" class="w-20 h-20 rounded-3xl object-cover" loading="lazy">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white" x-text="menu.name"></h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2" x-text="menu.description"></p>
                                <div class="mt-2 text-sm font-semibold text-primary-600" x-text="formatPrice(menu.price)"></div>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-black/20 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button @click.stop="addToCart(menu)" class="btn btn-sm bg-white text-gray-900 hover:bg-primary-600 hover:text-white">
                                + Keranjang
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Search & Sort Bar --}}
        <div class="bg-white dark:bg-dark-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-dark-700 mb-6">
            <div class="flex flex-col lg:flex-row gap-3 mb-4">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input
                        x-model="search"
                        type="text"
                        placeholder="🔍 Cari menu... contoh: ayam, pedas, minuman"
                        class="input pl-12 pr-10 text-sm w-full"
                    >
                    <button
                        x-show="search"
                        @click="search = ''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-200 dark:bg-dark-600 hover:bg-gray-300 rounded-full flex items-center justify-center transition-all"
                    >
                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <select x-model="sortBy" class="input lg:w-56 text-sm flex-shrink-0">
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

            {{-- Price & Promo Filter --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
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

            <div x-show="activeFiltersCount > 0" class="pt-3 border-t border-gray-100 dark:border-dark-700 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <p>
                    <span class="font-bold text-primary-600" x-text="activeFiltersCount"></span> filter aktif ·
                    <span class="font-bold text-gray-900 dark:text-white" x-text="filtered.length"></span> menu ditemukan
                    <span x-show="search"> untuk "<span class="text-primary-600 italic" x-text="search"></span>"</span>
                </p>
                <button @click="resetFilters()" class="text-red-500 hover:text-red-700 font-semibold hover:underline">✕ Reset semua filter</button>
            </div>
        </div>

        {{-- Category Filter --}}
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2 mb-6">
            <template x-for="cat in categories" :key="cat.id">
                <button
                    @click="activeCategory = cat.id"
                    :class="activeCategory === cat.id
                        ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30 border-primary-600'
                        : 'bg-gray-50 dark:bg-dark-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-600 border-gray-200 dark:border-dark-600'"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium text-sm whitespace-nowrap transition-all duration-200 border flex-shrink-0"
                >
                    <span x-text="cat.icon"></span>
                    <span x-text="cat.name"></span>
                </button>
            </template>
        </div>

        {{-- Results Count --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Menampilkan <span class="font-bold text-gray-900 dark:text-white" x-text="filtered.length"></span> menu tersedia
                <span x-show="search"> untuk "<span class="text-primary-600 italic" x-text="search"></span>"</span>
            </p>
        </div>

        {{-- Menu Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="menu in filtered" :key="menu.id">
                <div @click="openDetail(menu)"
                    :class="lastAddedId === menu.id ? 'ring-2 ring-primary-400/30 scale-[1.01]' : ''"
                    class="card card-hover group animate-fade-in transition-all duration-300">
                    {{-- Image --}}
                    <div class="relative overflow-hidden h-48">
                        <img
                            :src="menu.image"
                            :alt="menu.name"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            loading="lazy"
                        >
                        {{-- Labels --}}
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            <span x-show="menu.label === 'best-seller'" class="badge bg-primary-600 text-white text-xs">🔥 Best Seller</span>
                            <span x-show="menu.label === 'popular'" class="badge bg-blue-600 text-white text-xs">⭐ Popular</span>
                            <span x-show="menu.isNew" class="badge bg-green-600 text-white text-xs">✨ Baru</span>
                        </div>
                        <div x-show="menu.isPromo" class="absolute top-3 right-3">
                            <span class="badge bg-red-500 text-white text-xs">PROMO</span>
                        </div>

                        {{-- Quick Add Overlay --}}
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button
                                @click.stop="addToCart(menu)"
                                class="btn bg-white text-gray-900 hover:bg-primary-600 hover:text-white text-sm py-2 px-4 transform scale-90 group-hover:scale-100 transition-all duration-300"
                            >
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1 truncate" x-text="menu.name"></h3>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mb-3 line-clamp-2" x-text="menu.description"></p>

                        {{-- Rating --}}
                        <div class="flex items-center gap-1 mb-3">
                            <div class="flex">
                                <template x-for="i in 5" :key="i">
                                    <span :class="i <= Math.round(menu.rating) ? 'text-yellow-400' : 'text-gray-300'" class="text-sm">★</span>
                                </template>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="`${menu.rating} (${menu.reviews})`"></span>
                        </div>

                        {{-- Price & Add --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-primary-600 font-bold" x-text="formatPrice(menu.price)"></div>
                                <div x-show="menu.originalPrice" class="text-gray-400 text-xs line-through"
                                    x-text="menu.originalPrice ? formatPrice(menu.originalPrice) : ''"></div>
                            </div>
                            <button
                                @click.stop="addToCart(menu)"
                                class="w-9 h-9 bg-primary-600 hover:bg-primary-700 text-white rounded-lg flex items-center justify-center transition-all hover:scale-110 shadow-lg shadow-primary-600/30"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Empty State --}}
            <div
                x-show="filtered.length === 0"
                class="col-span-full text-center py-20"
            >
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Menu tidak ditemukan</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Coba kata kunci lain atau pilih kategori berbeda</p>
                <button @click="search = ''; activeCategory = 'all'" class="btn btn-primary">
                    Reset Filter
                </button>
            </div>
        </div>

    </div>

    {{-- Detail Popup --}}
    <div x-show="selectedMenu" x-cloak @click="closeDetail()" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70">
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


@endsection
