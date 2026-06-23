@extends('layouts.app')
@section('title', 'Reservasi Meja — Warung Nusantara')
@section('content')

{{-- Header --}}
<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-primary mb-3">Reservasi</span>
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
        <div class="max-w-3xl mx-auto scroll-mt-24" x-data='reservation(@json($menus ?? []))' x-ref="stepTop">

            {{-- Success State: Struk Pembayaran --}}
            <div x-show="submitted" class="animate-fade-in" id="receipt-wrapper">
                <div class="card p-0 overflow-hidden" id="receipt-card">

                    {{-- Header Struk --}}
                    <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6 text-white text-center no-print-hide">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-1">Pembayaran Berhasil!</h2>
                        <p class="text-primary-100 text-sm">Terima kasih, <span x-text="form.name" class="font-semibold"></span>!</p>
                    </div>

                    {{-- Print Header (hanya tampil saat print) --}}
                    <div class="print-only hidden p-6 border-b border-gray-200 text-center">
                        <h1 class="text-2xl font-bold text-gray-900">RESTORAN NUSANTARA</h1>
                        <p class="text-sm text-gray-500 mt-1">Bukti Pembayaran Reservasi</p>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="'Dicetak: ' + new Date().toLocaleString('id-ID')"></p>
                    </div>

                    <div class="p-6 space-y-6">

                        {{-- Kode Reservasi --}}
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Kode Reservasi</p>
                            <div class="font-mono text-3xl font-extrabold text-primary-600 tracking-widest" x-text="reservationCode || '#RES---------'"></div>
                            <p class="text-xs text-gray-400 mt-1" x-text="'Dibuat: ' + new Date().toLocaleString('id-ID')"></p>
                        </div>

                        {{-- Grid Detail --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Detail Reservasi --}}
                            <div class="bg-gray-50 dark:bg-dark-700 rounded-2xl p-4 space-y-2.5">
                                <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Detail Reservasi
                                </h3>
                                <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Nama</span><span class="font-semibold text-gray-900 dark:text-white" x-text="form.name"></span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Telepon</span><span class="font-semibold text-gray-900 dark:text-white" x-text="form.phone"></span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Tanggal</span><span class="font-semibold text-gray-900 dark:text-white" x-text="form.date"></span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Jam</span><span class="font-semibold text-gray-900 dark:text-white" x-text="form.time + ' WIB'"></span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Tamu</span><span class="font-semibold text-gray-900 dark:text-white" x-text="form.guests + ' orang'"></span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Area</span><span class="font-semibold text-gray-900 dark:text-white capitalize" x-text="form.tableArea"></span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Meja</span><span class="font-semibold text-gray-900 dark:text-white" x-text="getTableLabel(form.tableNumber)"></span></div>
                            </div>

                            {{-- Bukti Pembayaran sesuai metode --}}
                            <div class="rounded-2xl p-4 space-y-2.5 border-2"
                                :class="{
                                    'border-emerald-300 bg-emerald-50 dark:border-emerald-700 dark:bg-emerald-900/10': paymentMethod === 'cash',
                                    'border-blue-300 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/10': ['qris','dana','ovo','shopeepay','linkaja'].includes(paymentMethod),
                                    'border-indigo-300 bg-indigo-50 dark:border-indigo-700 dark:bg-indigo-900/10': ['mandiri','bri','bni','bca','permata','ocbc'].includes(paymentMethod)
                                }">
                                <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    Bukti Pembayaran
                                </h3>

                                {{-- TUNAI --}}
                                <template x-if="paymentMethod === 'cash'">
                                    <div class="text-center py-4">
                                        <div class="text-4xl mb-2">💵</div>
                                        <p class="font-bold text-emerald-700 dark:text-emerald-400">Bayar Tunai</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Silakan bayar di kasir saat kedatangan.</p>
                                        <div class="mt-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl px-4 py-2 text-sm font-bold text-emerald-700 dark:text-emerald-400">
                                            Status: Menunggu Pembayaran
                                        </div>
                                    </div>
                                </template>

                                {{-- E-WALLET --}}
                                <template x-if="['qris','dana','ovo','shopeepay','linkaja'].includes(paymentMethod)">
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-2xl">📲</span>
                                            <div>
                                                <p class="font-bold text-blue-700 dark:text-blue-400 capitalize" x-text="paymentMethod.toUpperCase()"></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">E-Wallet</p>
                                            </div>
                                        </div>
                                        {{-- QR Code simulasi --}}
                                        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 text-center border border-blue-200 dark:border-blue-800 mb-3">
                                            <div class="w-32 h-32 mx-auto bg-gray-100 dark:bg-dark-700 rounded-lg flex items-center justify-center mb-2 border border-dashed border-gray-300">
                                                <div class="text-center">
                                                    <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                    <p class="text-[10px] text-gray-400 mt-1">Scan QR</p>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Scan QR dengan aplikasi <span x-text="paymentMethod.toUpperCase()"></span></p>
                                        </div>
                                        <div class="flex justify-between text-sm py-1"><span class="text-gray-500">Merchant</span><span class="font-semibold text-gray-900 dark:text-white">Restoran Nusantara</span></div>
                                        <div class="flex justify-between text-sm py-1"><span class="text-gray-500">No. Ref</span><span class="font-mono font-semibold text-gray-900 dark:text-white" x-text="reservationCode || '—'"></span></div>
                                        <div class="mt-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl px-4 py-2 text-sm font-bold text-blue-700 dark:text-blue-400 text-center">
                                            Status: Menunggu Verifikasi
                                        </div>
                                    </div>
                                </template>

                                {{-- VIRTUAL ACCOUNT --}}
                                <template x-if="['mandiri','bri','bni','bca','permata','ocbc'].includes(paymentMethod)">
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-2xl">🏦</span>
                                            <div>
                                                <p class="font-bold text-indigo-700 dark:text-indigo-400"
                                                    x-text="{mandiri:'Bank Mandiri',bri:'Bank BRI',bni:'Bank BNI',bca:'Bank BCA',permata:'Bank Permata',ocbc:'Bank OCBC'}[paymentMethod]">
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Virtual Account</p>
                                            </div>
                                        </div>
                                        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 border border-indigo-200 dark:border-indigo-800 mb-3">
                                            <p class="text-xs text-gray-500 mb-1">Nomor Virtual Account</p>
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="font-mono text-lg font-bold text-indigo-700 dark:text-indigo-300 tracking-widest"
                                                    x-text="{mandiri:'88808',bri:'26215',bni:'8277',bca:'57799',permata:'8629',ocbc:'9999'}[paymentMethod] + (reservationCode || '00000000').replace('#RES-','')">
                                                </span>
                                                <button type="button"
                                                    @click="navigator.clipboard.writeText(document.querySelector('.va-number')?.textContent || ''); $el.textContent = 'Disalin!'; setTimeout(() => $el.textContent = 'Salin', 1500)"
                                                    class="text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 px-3 py-1.5 rounded-lg font-semibold hover:bg-indigo-200 transition-all no-print-hide">
                                                    Salin
                                                </button>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 text-sm">
                                            <div class="flex justify-between"><span class="text-gray-500">Bank</span><span class="font-semibold text-gray-900 dark:text-white" x-text="{mandiri:'Bank Mandiri',bri:'Bank BRI',bni:'Bank BNI',bca:'Bank BCA',permata:'Bank Permata',ocbc:'Bank OCBC'}[paymentMethod]"></span></div>
                                            <div class="flex justify-between"><span class="text-gray-500">Atas Nama</span><span class="font-semibold text-gray-900 dark:text-white">Restoran Nusantara</span></div>
                                            <div class="flex justify-between"><span class="text-gray-500">Batas Bayar</span><span class="font-semibold text-red-500">24 jam</span></div>
                                        </div>
                                        <div class="mt-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl px-4 py-2 text-sm font-bold text-yellow-700 dark:text-yellow-400 text-center">
                                            Status: Menunggu Transfer
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Daftar Menu Dipesan --}}
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Menu Dipesan
                            </h3>
                            <div class="divide-y divide-gray-100 dark:divide-dark-600">
                                <template x-for="item in selectedItems" :key="item.id">
                                    <div class="flex items-center gap-3 py-2.5">
                                        <img :src="item.image" :alt="item.name" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 no-print-hide">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="item.name"></p>
                                            <p class="text-xs text-gray-400" x-text="'x' + item.qty + ' · ' + formatPrice(item.price)"></p>
                                        </div>
                                        <p class="text-sm font-bold text-primary-600" x-text="formatPrice(item.price * item.qty)"></p>
                                    </div>
                                </template>
                            </div>
                            <div class="border-t-2 border-gray-200 dark:border-dark-600 mt-2 pt-3 flex items-center justify-between">
                                <span class="font-bold text-gray-900 dark:text-white">Total</span>
                                <span class="text-lg font-extrabold text-primary-600" x-text="formatPrice(selectedMenuTotal)"></span>
                            </div>
                        </div>

                        {{-- Footer Struk --}}
                        <div class="text-center text-xs text-gray-400 dark:text-gray-500 border-t border-dashed border-gray-200 dark:border-dark-600 pt-4">
                            <p class="font-semibold text-gray-600 dark:text-gray-400 mb-1">Restoran Nusantara</p>
                            <p>Terima kasih telah melakukan reservasi. Tunjukkan bukti ini kepada staff kami.</p>
                            <p class="mt-1">Pertanyaan? Hubungi kami di <span class="font-semibold">0812-3456-7890</span></p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="px-6 pb-6 flex flex-col sm:flex-row gap-3 no-print-hide">
                        <button @click="downloadReceipt()"
                            class="flex-1 flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 text-gray-700 dark:text-gray-200 font-bold text-sm transition-all border border-gray-200 dark:border-dark-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak / Unduh PDF
                        </button>
                        <button @click="reset()"
                            class="flex-1 flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition-all shadow-md shadow-primary-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Reservasi Baru
                        </button>
                    </div>
                </div>
            </div>

            {{-- Reservation Form --}}
            <div x-show="!submitted">

                {{-- Progress Steps --}}
                <div class="flex items-center justify-center mb-10">
                    <template x-for="(label, i) in ['Pilih Waktu & Meja', 'Pilih Menu', 'Detail Tamu', 'Konfirmasi']" :key="i">
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
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Reservasi</label>
                            <input x-model="form.date" type="date" :min="minDate" class="input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jumlah Tamu</label>
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
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Jam</label>
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
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Area Duduk</label>

                        {{-- Pilih Area --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            @foreach([
                                ['value' => 'indoor',  'icon' => '<svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>', 'label' => 'Indoor',  'desc' => 'Ruangan ber-AC'],
                                ['value' => 'outdoor', 'icon' => '<svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>', 'label' => 'Outdoor', 'desc' => 'Taman terbuka'],
                            ] as $area)
                            <button @click="selectArea('{{ $area['value'] }}')"
                                :class="form.tableArea === '{{ $area['value'] }}'
                                    ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30'
                                    : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'"
                                class="p-4 rounded-xl border-2 text-left transition-all duration-200">
                                <div class="w-8 h-8 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-2">{!! $area['icon'] !!}</div>
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
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M6 10v8m12-8v8M8 6h8a2 2 0 012 2v2H6V8a2 2 0 012-2z"/></svg>
                                <p class="text-sm text-primary-700 dark:text-primary-300 font-medium">
                                    Meja dipilih: <strong x-text="getTableLabel(form.tableNumber)"></strong>
                                </p>
                            </div>

                            {{-- Peringatan tidak ada meja --}}
                            <div x-show="form.tableArea && currentTables.filter(t => isTableSelectable(t)).length === 0" x-transition
                                class="mt-3 flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl px-4 py-2.5">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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
                <div x-show="step === 2" class="animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pilih Menu</h2>

                    {{-- ── Menu Pilihan (full width) ── --}}
                    <div class="card p-5 mb-6">
                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-base">Menu Pilihan</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pilih minimal 1 menu untuk reservasimu.</p>
                            </div>
                            <span class="text-sm font-bold text-primary-600"
                                x-text="selectedItems.length + ' item dipilih'"></span>
                        </div>

                        {{-- Search --}}
                        <div class="relative mb-3">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                            </svg>
                            <input x-model="menuSearch" type="text" placeholder="Cari menu..."
                                class="input pl-10 pr-9 py-2.5 text-sm w-full">
                            <button x-show="menuSearch" @click="menuSearch=''"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 bg-gray-200 dark:bg-dark-600 rounded-full flex items-center justify-center hover:bg-gray-300 transition-all">
                                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Category Filter Tabs --}}
                        <div class="flex gap-2 overflow-x-auto pb-2 mb-4" style="-webkit-overflow-scrolling: touch;">
                            <template x-for="cat in menuCategories" :key="cat.id">
                                <button @click="activeMenuCategory = cat.id"
                                    :class="activeMenuCategory === cat.id
                                        ? 'bg-primary-600 text-white border-primary-600 shadow-md shadow-primary-600/20'
                                        : 'bg-gray-50 dark:bg-dark-700 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-dark-600 hover:border-primary-400'"
                                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl font-medium text-xs whitespace-nowrap transition-all duration-200 border flex-shrink-0">
                                    <span x-text="cat.icon"></span>
                                    <span x-text="cat.name"></span>
                                </button>
                            </template>
                        </div>

                        {{-- Count --}}
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            Menampilkan <span class="font-bold text-gray-800 dark:text-white" x-text="filteredMenus.length"></span> menu
                        </p>

                            {{-- Menu Grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 max-h-[600px] overflow-y-auto pr-1">
                                <template x-for="menu in filteredMenus" :key="menu.id">
                                    <div class="group relative border rounded-2xl overflow-hidden transition-all duration-200 cursor-pointer"
                                        :class="selectedItems.find(i => i.id === menu.id)
                                            ? 'border-primary-500 ring-2 ring-primary-400/30 bg-primary-50 dark:bg-primary-900/20'
                                            : 'border-gray-200 dark:border-dark-700 bg-white dark:bg-dark-800 hover:border-primary-300 hover:shadow-md'"
                                        @click="toggleMenu(menu)">

                                        {{-- Image --}}
                                        <div class="relative h-32 overflow-hidden">
                                            <img :src="menu.image" :alt="menu.name"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            {{-- Labels --}}
                                            <div class="absolute top-2 left-2 flex flex-col gap-1">
                                                <span x-show="menu.label === 'best-seller'"
                                                    class="text-[9px] uppercase tracking-wider bg-orange-500 text-white px-2 py-0.5 rounded-full font-extrabold">Best Seller</span>
                                                <span x-show="menu.label === 'popular'"
                                                    class="text-[9px] uppercase tracking-wider bg-blue-500 text-white px-2 py-0.5 rounded-full font-extrabold">Populer</span>
                                                <span x-show="menu.isNew"
                                                    class="text-[9px] uppercase tracking-wider bg-emerald-500 text-white px-2 py-0.5 rounded-full font-extrabold">Baru</span>
                                            </div>
                                            {{-- Promo badge --}}
                                            <div x-show="menu.isPromo" class="absolute top-2 right-2">
                                                <span class="text-[9px] uppercase tracking-wider bg-red-500 text-white px-2 py-0.5 rounded-full font-extrabold">PROMO</span>
                                            </div>
                                            {{-- Stok habis overlay --}}
                                            <div x-show="menu.isStock === false"
                                                class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold bg-red-600 px-3 py-1 rounded-full">Habis</span>
                                            </div>
                                            {{-- Selected checkmark --}}
                                            <div x-show="selectedItems.find(i => i.id === menu.id)"
                                                class="absolute inset-0 bg-primary-600/20 flex items-center justify-center">
                                                <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center shadow-lg">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info --}}
                                        <div class="p-3">
                                            <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate" x-text="menu.name"></h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5" x-text="menu.description || ''"></p>

                                            {{-- Rating --}}
                                            <div class="flex items-center gap-1 mt-1.5 mb-2">
                                                <template x-for="i in 5" :key="i">
                                                    <span :class="i <= Math.round(menu.rating || 0) ? 'text-yellow-400' : 'text-gray-300'" class="text-xs">★</span>
                                                </template>
                                                <span class="text-[10px] text-gray-400 ml-0.5" x-text="(menu.rating || 0).toFixed(1)"></span>
                                            </div>

                                            {{-- Harga & tombol --}}
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-sm font-bold text-primary-600" x-text="formatPrice(menu.price)"></div>
                                                    <div x-show="menu.originalPrice" class="text-[10px] text-gray-400 line-through"
                                                        x-text="menu.originalPrice ? formatPrice(menu.originalPrice) : ''"></div>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    {{-- Kurangi qty jika sudah dipilih --}}
                                                    <button x-show="selectedItems.find(i => i.id === menu.id)"
                                                        @click.stop="decreaseMenu(menu)"
                                                        class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-200 font-bold text-sm flex items-center justify-center hover:bg-gray-200 transition-all">
                                                        −
                                                    </button>
                                                    <span x-show="selectedItems.find(i => i.id === menu.id)"
                                                        class="text-sm font-bold text-gray-900 dark:text-white w-5 text-center"
                                                        x-text="(selectedItems.find(i => i.id === menu.id) || {qty:0}).qty"></span>
                                                    <button
                                                        :disabled="menu.isStock === false"
                                                        :class="menu.isStock === false ? 'opacity-40 cursor-not-allowed bg-gray-200 text-gray-400' :
                                                            selectedItems.find(i => i.id === menu.id)
                                                                ? 'bg-primary-600 text-white hover:bg-primary-700'
                                                                : 'bg-primary-50 text-primary-600 hover:bg-primary-100'"
                                                        class="w-7 h-7 rounded-lg font-bold text-sm flex items-center justify-center transition-all"
                                                        @click.stop="toggleMenu(menu)">
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Empty --}}
                                <div x-show="filteredMenus.length === 0" class="col-span-full text-center py-12">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-dark-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Menu tidak ditemukan</p>
                                    <button @click="menuSearch=''; activeMenuCategory='all'" class="text-xs text-primary-600 font-semibold mt-1 hover:underline">Reset filter</button>
                                </div>
                            </div>
                    </div>{{-- end card menu --}}

                    {{-- ── Ringkasan + Metode Pembayaran muncul setelah pilih menu ── --}}
                    <div x-show="selectedItems.length > 0" x-transition class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                        {{-- Ringkasan Pesanan --}}
                        <div class="card p-5 bg-gray-50 dark:bg-dark-700"
                            x-data="{
                                voucherCode: '',
                                voucherMsg: '',
                                voucherType: '',
                                discount: 0,
                                appliedVoucher: null,
                                vouchers: {
                                    'HEMAT10':  { type: 'pct', value: 10,    label: 'Diskon 10%' },
                                    'GRATIS20': { type: 'fix', value: 20000, label: 'Potongan Rp 20.000' },
                                    'NEWUSER':  { type: 'pct', value: 15,    label: 'Diskon 15% New User' },
                                    'NUSANTARA':{ type: 'pct', value: 20,    label: 'Diskon 20% Spesial' },
                                },
                                applyVoucher(subtotal) {
                                    const code = this.voucherCode.trim().toUpperCase();
                                    const v = this.vouchers[code];
                                    if (!v) {
                                        this.voucherMsg = 'Kode voucher tidak valid.';
                                        this.voucherType = 'error';
                                        this.discount = 0;
                                        this.appliedVoucher = null;
                                        return;
                                    }
                                    this.discount = v.type === 'pct'
                                        ? Math.round(subtotal * v.value / 100)
                                        : Math.min(v.value, subtotal);
                                    this.appliedVoucher = { code, ...v };
                                    this.voucherMsg = v.label + ' berhasil diterapkan!';
                                    this.voucherType = 'success';
                                },
                                removeVoucher() {
                                    this.voucherCode = '';
                                    this.voucherMsg = '';
                                    this.voucherType = '';
                                    this.discount = 0;
                                    this.appliedVoucher = null;
                                }
                            }">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-3">Ringkasan Pesanan</h3>

                            {{-- Item list --}}
                            <template x-for="item in selectedItems" :key="item.id">
                                <div class="flex items-center gap-3 py-2.5 border-b border-gray-200 dark:border-dark-600 last:border-0">
                                    <img :src="item.image" :alt="item.name" class="w-11 h-11 rounded-xl object-cover flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="item.name"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            x<span x-text="item.qty"></span>
                                            <span class="mx-1">·</span>
                                            <span x-text="formatPrice(item.price)"></span>
                                        </div>
                                    </div>
                                    <div class="text-sm font-bold text-primary-600 flex-shrink-0" x-text="formatPrice(item.price * item.qty)"></div>
                                </div>
                            </template>

                            {{-- Subtotal --}}
                            <div class="pt-3 mt-1 flex items-center justify-between border-t border-gray-200 dark:border-dark-600">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="formatPrice(selectedMenuTotal)"></span>
                            </div>

                            {{-- Diskon voucher --}}
                            <div x-show="discount > 0" class="flex items-center justify-between py-1.5">
                                <span class="text-sm text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                                    Voucher <span class="font-bold" x-text="appliedVoucher?.code"></span>
                                </span>
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" x-text="'− ' + formatPrice(discount)"></span>
                            </div>

                            {{-- Total akhir --}}
                            <div class="pt-2 mt-1 flex items-center justify-between border-t-2 border-gray-300 dark:border-dark-500">
                                <span class="font-bold text-gray-900 dark:text-white">Total Bayar</span>
                                <span class="text-lg font-extrabold text-primary-600"
                                    x-text="formatPrice(Math.max(0, selectedMenuTotal - discount))"></span>
                            </div>

                            {{-- Input Voucher --}}
                            <div class="mt-4 pt-4 border-t border-dashed border-gray-300 dark:border-dark-500">
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/></svg>
                                    Kode Voucher
                                </p>

                                {{-- Applied badge --}}
                                <div x-show="appliedVoucher" x-transition
                                    class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl px-4 py-2.5 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <div>
                                            <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400 font-mono" x-text="appliedVoucher?.code"></span>
                                            <span class="text-xs text-emerald-600 dark:text-emerald-500 ml-1.5" x-text="appliedVoucher?.label"></span>
                                        </div>
                                    </div>
                                    <button @click="removeVoucher()"
                                        class="text-xs text-red-500 hover:text-red-700 font-semibold hover:underline ml-3 flex-shrink-0">
                                        Hapus
                                    </button>
                                </div>

                                {{-- Input row --}}
                                <div x-show="!appliedVoucher" class="flex gap-2">
                                    <div class="relative flex-1">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                                        </svg>
                                        <input
                                            x-model="voucherCode"
                                            @keydown.enter.prevent="applyVoucher(selectedMenuTotal)"
                                            type="text"
                                            placeholder="Masukkan kode voucher"
                                            class="input pl-9 pr-3 py-2.5 text-sm w-full"
                                            style="text-transform: uppercase;"
                                        >
                                    </div>
                                    <button @click="applyVoucher(selectedMenuTotal)"
                                        :disabled="!voucherCode.trim()"
                                        :class="voucherCode.trim()
                                            ? 'bg-primary-600 hover:bg-primary-700 text-white shadow-sm'
                                            : 'bg-gray-200 dark:bg-dark-600 text-gray-400 cursor-not-allowed'"
                                        class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex-shrink-0 whitespace-nowrap">
                                        Pakai
                                    </button>
                                </div>

                                {{-- Error --}}
                                <div x-show="voucherMsg && voucherType === 'error'" x-transition
                                    class="mt-2 flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="voucherMsg"></span>
                                </div>

                                {{-- Hint --}}
                                <p x-show="!appliedVoucher" class="mt-2 text-[10px] text-gray-400 dark:text-gray-500">
                                    Contoh: <span class="font-mono font-semibold text-gray-500">HEMAT10</span> · <span class="font-mono font-semibold text-gray-500">GRATIS20</span> · <span class="font-mono font-semibold text-gray-500">NUSANTARA</span>
                                </p>
                            </div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="card p-5"
                            x-data="{
                                expandedMethod: null,
                                eWallets: [
                                    { id: 'qris',      name: 'QRIS',       logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/320px-Logo_QRIS.svg.png',      desc: 'Scan QR untuk bayar dari semua aplikasi' },
                                    { id: 'dana',      name: 'DANA',       logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/320px-Logo_dana_blue.svg.png', desc: 'Bayar via aplikasi DANA' },
                                    { id: 'ovo',       name: 'OVO',        logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/320px-Logo_ovo_purple.svg.png', desc: 'Bayar via aplikasi OVO' },
                                    { id: 'shopeepay', name: 'ShopeePay', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/ShopeePay_Logo.svg/320px-ShopeePay_Logo.svg.png', desc: 'Bayar via ShopeePay / SpayLater' },
                                    { id: 'linkaja',   name: 'LinkAja',    logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/LinkAja.svg/320px-LinkAja.svg.png',   desc: 'Bayar via aplikasi LinkAja' },
                                ],
                                banks: [
                                    { id: 'mandiri', name: 'Bank Mandiri',                    color: '#003f88', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/320px-Bank_Mandiri_logo_2016.svg.png' },
                                    { id: 'bri',     name: 'Bank Rakyat Indonesia (BRI)',     color: '#003087', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/320px-BANK_BRI_logo.svg.png' },
                                    { id: 'bni',     name: 'Bank Negara Indonesia (BNI)',     color: '#e65c00', logo: 'https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/320px-BNI_logo.svg.png' },
                                    { id: 'bca',     name: 'Bank Central Asia (BCA)',         color: '#035AA6', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/320px-Bank_Central_Asia.svg.png' },
                                    { id: 'permata', name: 'Bank Permata',                    color: '#e31837', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fb/Bank_Permata_logo_2020.svg/320px-Bank_Permata_logo_2020.svg.png' },
                                    { id: 'ocbc',    name: 'Bank OCBC',                       color: '#e31837', logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/OCBC_NISP.svg/320px-OCBC_NISP.svg.png' },
                                ],
                            }">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-3">Metode Pembayaran</h3>
                            <div class="space-y-2.5">

                                {{-- Tunai --}}
                                <label class="p-3.5 rounded-2xl border cursor-pointer transition-all duration-200 flex items-center gap-3"
                                    :class="paymentMethod === 'cash'
                                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30'
                                        : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="cash" @change="expandedMethod=null" class="hidden">
                                    <span class="text-2xl leading-none">💵</span>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm">Tunai</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Bayar tunai saat kedatangan.</div>
                                    </div>
                                    <div :class="paymentMethod === 'cash' ? 'border-primary-600 bg-primary-600' : 'border-gray-300 dark:border-dark-500'"
                                        class="w-4 h-4 rounded-full border-2 flex-shrink-0 transition-all flex items-center justify-center">
                                        <div x-show="paymentMethod === 'cash'" class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                </label>

                                {{-- E-Wallet (accordion) --}}
                                <div class="rounded-2xl border transition-all duration-200"
                                    :class="['qris','dana','ovo','shopeepay','linkaja'].includes(paymentMethod)
                                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20'
                                        : 'border-gray-200 dark:border-dark-600'">

                                    {{-- Header toggle --}}
                                    <button type="button"
                                        @click="expandedMethod = expandedMethod === 'ewallet' ? null : 'ewallet'"
                                        class="w-full p-3.5 flex items-center gap-3 text-left">
                                        <span class="text-2xl leading-none">📲</span>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-white text-sm">E-Wallet</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span x-show="!['qris','dana','ovo','shopeepay','linkaja'].includes(paymentMethod)">QRIS, DANA, OVO, ShopeePay, LinkAja</span>
                                                <span x-show="['qris','dana','ovo','shopeepay','linkaja'].includes(paymentMethod)"
                                                    class="text-primary-600 font-semibold"
                                                    x-text="eWallets.find(e => e.id === paymentMethod)?.name + ' dipilih'"></span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div x-show="['qris','dana','ovo','shopeepay','linkaja'].includes(paymentMethod)"
                                                class="w-4 h-4 rounded-full border-2 border-primary-600 bg-primary-600 flex items-center justify-center">
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                                :class="expandedMethod === 'ewallet' ? 'rotate-180' : ''"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>

                                    {{-- Sub-options --}}
                                    <div x-show="expandedMethod === 'ewallet'" x-transition
                                        class="px-3 pb-3 grid grid-cols-1 gap-2">
                                        <template x-for="ew in eWallets" :key="ew.id">
                                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                                :class="paymentMethod === ew.id
                                                    ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/30'
                                                    : 'border-gray-100 dark:border-dark-600 hover:border-primary-300 bg-white dark:bg-dark-700'">
                                                <input type="radio" x-model="paymentMethod" :value="ew.id" class="hidden">
                                                <img :src="ew.logo" :alt="ew.name"
                                                    class="h-7 w-16 object-contain flex-shrink-0"
                                                    x-on:error="$el.style.display='none'">
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-semibold text-gray-800 dark:text-white" x-text="ew.name"></div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400" x-text="ew.desc"></div>
                                                </div>
                                                <div :class="paymentMethod === ew.id ? 'border-primary-600 bg-primary-600' : 'border-gray-300 dark:border-dark-500'"
                                                    class="w-4 h-4 rounded-full border-2 flex-shrink-0 transition-all flex items-center justify-center">
                                                    <div x-show="paymentMethod === ew.id" class="w-2 h-2 bg-white rounded-full"></div>
                                                </div>
                                            </label>
                                        </template>
                                    </div>
                                </div>

                                {{-- Virtual Account (accordion) --}}
                                <div class="rounded-2xl border transition-all duration-200"
                                    :class="['mandiri','bri','bni','bca','permata','ocbc'].includes(paymentMethod)
                                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20'
                                        : 'border-gray-200 dark:border-dark-600'">

                                    {{-- Header toggle --}}
                                    <button type="button"
                                        @click="expandedMethod = expandedMethod === 'va' ? null : 'va'"
                                        class="w-full p-3.5 flex items-center gap-3 text-left">
                                        <span class="text-2xl leading-none">🏦</span>
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-white text-sm">Virtual Account</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span x-show="!['mandiri','bri','bni','bca','permata','ocbc'].includes(paymentMethod)">Mandiri, BRI, BNI, BCA, Permata, OCBC</span>
                                                <span x-show="['mandiri','bri','bni','bca','permata','ocbc'].includes(paymentMethod)"
                                                    class="text-primary-600 font-semibold"
                                                    x-text="banks.find(b => b.id === paymentMethod)?.name + ' dipilih'"></span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div x-show="['mandiri','bri','bni','bca','permata','ocbc'].includes(paymentMethod)"
                                                class="w-4 h-4 rounded-full border-2 border-primary-600 bg-primary-600 flex items-center justify-center">
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                                :class="expandedMethod === 'va' ? 'rotate-180' : ''"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>

                                    {{-- Sub-options --}}
                                    <div x-show="expandedMethod === 'va'" x-transition
                                        class="px-3 pb-3 grid grid-cols-1 gap-2">
                                        <template x-for="bank in banks" :key="bank.id">
                                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                                :class="paymentMethod === bank.id
                                                    ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/30'
                                                    : 'border-gray-100 dark:border-dark-600 hover:border-primary-300 bg-white dark:bg-dark-700'">
                                                <input type="radio" x-model="paymentMethod" :value="bank.id" class="hidden">
                                                <img :src="bank.logo" :alt="bank.name"
                                                    class="h-7 w-20 object-contain flex-shrink-0"
                                                    x-on:error="$el.style.display='none'">
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-semibold text-gray-800 dark:text-white truncate" x-text="bank.name"></div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Transfer via Virtual Account</div>
                                                </div>
                                                <div :class="paymentMethod === bank.id ? 'border-primary-600 bg-primary-600' : 'border-gray-300 dark:border-dark-500'"
                                                    class="w-4 h-4 rounded-full border-2 flex-shrink-0 transition-all flex items-center justify-center">
                                                    <div x-show="paymentMethod === bank.id" class="w-2 h-2 bg-white rounded-full"></div>
                                                </div>
                                            </label>
                                        </template>
                                    </div>
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
                <div x-show="step === 3" class="animate-fade-in">
                    <div class="card p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Detail Tamu</h2>

                        {{-- Form fields --}}
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

                        {{-- DP Section: 2 kolom --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                            {{-- Kolom 1: Form DP --}}
                            <div class="rounded-2xl border border-gray-200 dark:border-dark-600 p-4">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Pembayaran DP</h3>
                                </div>
                                <div class="space-y-4">
                                    {{-- Nominal DP --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Nominal DP</label>
                                        <div class="relative">
                                            <select x-model="dpNominal" @change="dpNominal = parseInt($event.target.value)" class="input py-2.5 pr-8 text-sm appearance-none w-full">
                                                <option value="50000">Rp 50.000</option>
                                                <option value="100000" selected>Rp 100.000</option>
                                                <option value="150000">Rp 150.000</option>
                                                <option value="200000">Rp 200.000</option>
                                                <option value="250000">Rp 250.000</option>
                                                <option value="300000">Rp 300.000</option>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom 3: Ringkasan Reservasi --}}
                            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/10 p-4">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Ringkasan Reservasi</h3>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Nomor Meja</span>
                                        <span class="font-semibold text-gray-900 dark:text-white" x-text="form.tableNumber || '—'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Tanggal</span>
                                        <span class="font-semibold text-gray-900 dark:text-white" x-text="form.date || '—'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Jam</span>
                                        <span class="font-semibold text-gray-900 dark:text-white" x-text="form.time ? form.time + ' WIB' : '—'"></span>
                                    </div>
                                    <div class="border-t border-emerald-200 dark:border-emerald-700 pt-2 mt-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Total Pesanan</span>
                                            <span class="font-semibold text-gray-900 dark:text-white" x-text="formatPrice(selectedMenuTotal)"></span>
                                        </div>
                                        <div>
                                            <div class="flex justify-between mt-1.5">
                                                <span class="text-gray-500 dark:text-gray-400">DP Dibayar</span>
                                                <span class="font-bold text-primary-600" x-text="formatPrice(parseInt(dpNominal))"></span>
                                            </div>
                                            <div class="flex justify-between mt-1.5">
                                                <span class="text-gray-500 dark:text-gray-400">Sisa Pembayaran</span>
                                                <span class="font-bold text-red-500" x-text="formatPrice(sisa)"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 bg-white dark:bg-dark-700 rounded-xl p-3 flex items-start gap-2 border border-emerald-200 dark:border-emerald-700">
                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Sisa pembayaran dapat dilakukan saat datang ke restoran.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Catatan DP --}}
                        <div class="mb-6 flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl px-4 py-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-amber-700 dark:text-amber-400">
                                <strong>Catatan:</strong> Reservasi akan dikonfirmasi setelah pembayaran DP berhasil diverifikasi oleh admin restoran.
                            </p>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex gap-3">
                            <button @click="prevStep()" class="btn btn-secondary flex-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                </svg>
                                Kembali
                            </button>
                            <button @click="nextStep()"
                                :disabled="!form.name || !form.phone"
                                :class="(!form.name || !form.phone) ? 'opacity-50 cursor-not-allowed' : ''"
                                class="btn btn-primary flex-1">
                                <span>Bayar & Lanjutkan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Confirmation --}}
                <div x-show="step === 4" class="card p-8 animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Konfirmasi Reservasi</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Periksa kembali detail reservasi sebelum dikonfirmasi.</p>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

                        {{-- Kolom Kiri: Detail Reservasi & Tamu --}}
                        <div class="bg-gray-50 dark:bg-dark-700 rounded-2xl p-5 space-y-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Detail Reservasi</h3>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Tanggal</span>
                                <span class="font-semibold text-gray-900 dark:text-white" x-text="form.date"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Jam</span>
                                <span class="font-semibold text-gray-900 dark:text-white" x-text="form.time + ' WIB'"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Jumlah Tamu</span>
                                <span class="font-semibold text-gray-900 dark:text-white" x-text="form.guests + ' orang'"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Area</span>
                                <span class="font-semibold text-gray-900 dark:text-white capitalize" x-text="form.tableArea"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Meja</span>
                                <span class="font-semibold text-gray-900 dark:text-white" x-text="getTableLabel(form.tableNumber)"></span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-dark-600 pt-3 mt-1 space-y-3">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Detail Tamu</h3>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Nama</span>
                                    <span class="font-semibold text-gray-900 dark:text-white" x-text="form.name"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Telepon</span>
                                    <span class="font-semibold text-gray-900 dark:text-white" x-text="form.phone"></span>
                                </div>
                                <div x-show="form.email" class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                                    <span class="font-semibold text-gray-900 dark:text-white" x-text="form.email"></span>
                                </div>
                                <div x-show="form.notes" class="flex justify-between text-sm gap-4">
                                    <span class="text-gray-500 dark:text-gray-400 flex-shrink-0">Catatan</span>
                                    <span class="font-semibold text-gray-900 dark:text-white text-right" x-text="form.notes"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Menu + Pembayaran --}}
                        <div class="space-y-4">

                            {{-- Daftar Menu --}}
                            <div class="bg-gray-50 dark:bg-dark-700 rounded-2xl p-5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Menu Dipesan</h3>
                                <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                    <template x-for="item in selectedItems" :key="item.id">
                                        <div class="flex items-center gap-3">
                                            <img :src="item.image" :alt="item.name" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="item.name"></p>
                                                <p class="text-xs text-gray-400" x-text="'x' + item.qty"></p>
                                            </div>
                                            <p class="text-sm font-bold text-primary-600 flex-shrink-0" x-text="formatPrice(item.price * item.qty)"></p>
                                        </div>
                                    </template>
                                </div>
                                <div class="border-t border-gray-200 dark:border-dark-600 mt-3 pt-3 flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Total Menu</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="formatPrice(selectedMenuTotal)"></span>
                                </div>
                            </div>

                            {{-- Ringkasan Pembayaran --}}
                            <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-2xl p-5 space-y-2.5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-primary-500 mb-3">Pembayaran</h3>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Metode Pembayaran</span>
                                    <span class="font-semibold text-gray-900 dark:text-white capitalize"
                                        x-text="{
                                            cash: 'Tunai',
                                            qris: 'QRIS', dana: 'DANA', ovo: 'OVO', shopeepay: 'ShopeePay', linkaja: 'LinkAja',
                                            mandiri: 'Bank Mandiri', bri: 'Bank BRI', bni: 'Bank BNI',
                                            bca: 'Bank BCA', permata: 'Bank Permata', ocbc: 'Bank OCBC'
                                        }[paymentMethod] || paymentMethod">
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Total Pesanan</span>
                                    <span class="font-semibold text-gray-900 dark:text-white" x-text="formatPrice(selectedMenuTotal)"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">DP Dibayar</span>
                                    <span class="font-bold text-primary-600" x-text="formatPrice(parseInt(dpNominal))"></span>
                                </div>
                                <div class="border-t border-primary-200 dark:border-primary-700 pt-2.5 flex justify-between text-sm">
                                    <span class="font-bold text-gray-700 dark:text-gray-300">Sisa Pembayaran</span>
                                    <span class="font-extrabold text-red-500" x-text="formatPrice(sisa)"></span>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    Sisa pembayaran dibayarkan saat datang ke restoran.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div x-show="message" x-transition
                        :class="messageType === 'error'
                            ? 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-700/50 text-red-700 dark:text-red-300'
                            : 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-700/50 text-green-700 dark:text-green-300'"
                        class="mb-6 p-4 rounded-xl border">
                        <div class="flex items-start gap-3">
                            <svg x-show="messageType === 'error'" class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <svg x-show="messageType !== 'error'" class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
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
                            <span x-show="!loading" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Konfirmasi Reservasi
                            </span>
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
