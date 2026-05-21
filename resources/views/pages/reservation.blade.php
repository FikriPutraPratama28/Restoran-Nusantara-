@extends('layouts.app')
@section('title', 'Reservasi Meja — Warung Nusantara')
@section('content')

{{-- Header --}}
<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-primary mb-3">📅 Reservasi</span>
        <h1 class="font-display text-4xl md:text-6xl font-bold text-white mb-4">
            Booking <span class="gradient-text">Meja</span>
        </h1>
        <p class="text-gray-400 max-w-xl mx-auto">
            Reservasi meja sekarang dan pastikan tempat dudukmu sudah tersedia
        </p>
    </div>
</div>

<section class="section bg-gray-50 dark:bg-dark-900">
    <div class="container-custom">
        <div class="max-w-3xl mx-auto" x-data="reservation(@json($menus ?? []))">

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
                    telah dikonfirmasi.
                </p>
                <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 inline-block text-left mb-8 shadow-lg">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Kode Reservasi</div>
                    <div class="font-mono text-2xl font-bold text-primary-600">
                        #RES-{{ strtoupper(substr(md5(time()), 0, 8)) }}
                    </div>
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                        🪑 <span x-text="getTableLabel(form.tableNumber)"></span>
                        &nbsp;·&nbsp; <span class="capitalize" x-text="form.tableArea"></span>
                    </div>
                </div>
                <br>
                <button @click="reset()" class="btn btn-primary">Buat Reservasi Baru</button>
            </div>

            {{-- Reservation Form --}}
            <div x-show="!submitted">

                {{-- Progress Steps --}}
                <div class="flex items-center justify-center mb-10">
                    <template x-for="(label, i) in ['Pilih Waktu & Meja', 'Pilih Menu & Bayar', 'Detail Tamu', 'Konfirmasi']" :key="i">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center">
                                <div :class="step > i+1 ? 'bg-green-500 text-white' : step === i+1 ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-dark-700 text-gray-500'"
                                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300">
                                    <span x-show="step <= i+1" x-text="i+1"></span>
                                    <svg x-show="step > i+1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-xs mt-1 font-medium whitespace-nowrap"
                                    :class="step === i+1 ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400'"
                                    x-text="label"></span>
                            </div>
                            <div x-show="i < 3" class="w-12 md:w-20 h-0.5 mx-2 mb-4"
                                :class="step > i+1 ? 'bg-green-500' : 'bg-gray-200 dark:bg-dark-700'"></div>
                        </div>
                    </template>
                </div>

                {{-- Step 1: Date, Time & Table --}}
                <div x-show="step === 1" class="card p-8 animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pilih Waktu & Meja</h2>

                    {{-- Date & Guests --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">📅 Tanggal Reservasi</label>
                            <input x-model="form.date" type="date" :min="minDate" class="input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">👥 Jumlah Tamu</label>
                            <div class="flex items-center gap-3">
                                <button @click="form.guests = Math.max(1, form.guests - 1); form.tableNumber = null"
                                    class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-dark-700 flex items-center justify-center font-bold hover:bg-primary-100 dark:hover:bg-primary-900 transition-all">−</button>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white w-12 text-center" x-text="form.guests"></span>
                                <button @click="form.guests = Math.min(20, form.guests + 1); form.tableNumber = null"
                                    class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-dark-700 flex items-center justify-center font-bold hover:bg-primary-100 dark:hover:bg-primary-900 transition-all">+</button>
                                <span class="text-gray-500 dark:text-gray-400 text-sm">orang</span>
                            </div>
                        </div>
                    </div>

                    {{-- Time Slots --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🕐 Pilih Jam</label>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                            <template x-for="time in timeSlots" :key="time">
                                <button @click="form.time = time"
                                    :class="form.time === time ? 'bg-primary-600 text-white border-primary-600' : 'bg-white dark:bg-dark-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-dark-600 hover:border-primary-400'"
                                    class="py-2 px-3 rounded-lg border text-sm font-medium transition-all duration-200"
                                    x-text="time"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Area Duduk + Pilih Meja --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🪑 Area Duduk</label>

                        {{-- Pilih Area --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            @foreach([
                                ['value' => 'indoor',  'icon' => '🏠', 'label' => 'Indoor',  'desc' => 'Ruangan ber-AC'],
                                ['value' => 'outdoor', 'icon' => '🌿', 'label' => 'Outdoor', 'desc' => 'Taman terbuka'],
                            ] as $area)
                            <button @click="selectArea('{{ $area['value'] }}')"
                                :class="form.tableArea === '{{ $area['value'] }}'
                                    ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30'
                                    : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'"
                                class="p-4 rounded-xl border-2 text-left transition-all duration-200">
                                <div class="text-2xl mb-1">{{ $area['icon'] }}</div>
                                <div class="font-bold text-gray-900 dark:text-white text-sm">{{ $area['label'] }}</div>
                                <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $area['desc'] }}</div>
                            </button>
                            @endforeach
                        </div>

                        {{-- Pilih Meja --}}
                        <div x-show="form.tableArea" x-transition class="mt-2">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Pilih Meja
                                    <span class="text-xs font-normal text-gray-400 ml-1">(untuk <span x-text="form.guests"></span> orang)</span>
                                </p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Tersedia</span>
                                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400 inline-block"></span> Dipesan</span>
                                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span> Penuh</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <template x-for="table in currentTables" :key="table.id">
                                    <button @click="selectTable(table)"
                                        :disabled="!isTableSelectable(table)"
                                        :class="{
                                            'border-primary-600 bg-primary-50 dark:bg-primary-900/30 ring-2 ring-primary-500': form.tableNumber === table.id,
                                            'border-emerald-300 dark:border-emerald-700 hover:border-primary-400 hover:bg-gray-50 dark:hover:bg-dark-700 cursor-pointer': isTableSelectable(table) && form.tableNumber !== table.id,
                                            'border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/20 opacity-60 cursor-not-allowed': table.status === 'occupied',
                                            'border-yellow-200 dark:border-yellow-900 bg-yellow-50 dark:bg-yellow-900/20 opacity-60 cursor-not-allowed': table.status === 'reserved',
                                            'border-gray-200 dark:border-dark-600 opacity-50 cursor-not-allowed': table.status === 'available' && table.capacity < form.guests
                                        }"
                                        class="p-3 rounded-xl border-2 text-left transition-all duration-200 relative">
                                        {{-- Status dot --}}
                                        <div class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full"
                                            :class="{
                                                'bg-emerald-500': table.status === 'available' && table.capacity >= form.guests,
                                                'bg-yellow-400': table.status === 'reserved',
                                                'bg-red-400': table.status === 'occupied',
                                                'bg-gray-300': table.status === 'available' && table.capacity < form.guests
                                            }"></div>
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

                            {{-- Info meja terpilih --}}
                            <div x-show="form.tableNumber" x-transition
                                class="mt-3 flex items-center gap-2 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-xl px-4 py-2.5">
                                <span class="text-primary-600 text-lg">🪑</span>
                                <p class="text-sm text-primary-700 dark:text-primary-300 font-medium">
                                    Meja dipilih: <strong x-text="getTableLabel(form.tableNumber)"></strong>
                                </p>
                            </div>

                            {{-- Peringatan tidak ada meja --}}
                            <div x-show="form.tableArea && currentTables.filter(t => isTableSelectable(t)).length === 0" x-transition
                                class="mt-3 flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl px-4 py-2.5">
                                <span class="text-red-500 text-lg">⚠️</span>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Tidak ada meja tersedia untuk <strong x-text="form.guests + ' orang'"></strong> di area ini.
                                    Coba kurangi jumlah tamu atau pilih area lain.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button @click="nextStep()" :disabled="!canProceed" :class="!canProceed ? 'opacity-50 cursor-not-allowed' : ''"
                        class="btn btn-primary w-full">
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
                            <span class="text-gray-500 dark:text-gray-400">🏠 Area</span>
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

                    {{-- Message --}}
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
                        <button @click="submit()" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn btn-primary flex-1 flex items-center justify-center gap-2">
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

@endsection
