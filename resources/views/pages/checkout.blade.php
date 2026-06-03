@extends('layouts.app')

@section('title', 'Checkout — Restoran NUSANTARA')

@section('content')

<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-primary mb-3">🛒 Checkout</span>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-4">
            Selesaikan <span class="gradient-text">Pesanan</span>
        </h1>
    </div>
</div>

<section class="section bg-gray-50 dark:bg-dark-900" x-data="checkout()">

    {{-- Order Success --}}
    <div x-show="orderPlaced" class="container-custom max-w-lg mx-auto text-center py-16 animate-fade-in">
        <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h2 class="font-display text-3xl font-bold text-gray-900 dark:text-white mb-4">Pesanan Berhasil!</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-2">
            Metode pembayaran: <span class="font-semibold text-gray-900 dark:text-white" x-text="paymentLabel()"></span>
        </p>
        <p class="text-gray-600 dark:text-gray-400 mb-6" x-text="paymentStatus"></p>
        <p class="text-gray-600 dark:text-gray-400 mb-8">Kode pesanan: <span class="font-semibold text-gray-900 dark:text-white" x-text="orderReference"></span></p>
        <p x-show="proofFileName" class="text-gray-600 dark:text-gray-400 mb-8">Bukti transfer: <span class="font-semibold text-gray-900 dark:text-white" x-text="proofFileName"></span></p>

        {{-- Order Status --}}
        <div class="card p-6 mb-8" x-data="orderStatus()">
            <h3 class="font-bold text-gray-900 dark:text-white mb-6 text-left">Status Pesanan</h3>
            <div class="space-y-4">
                <template x-for="status in statuses" :key="status.id">
                    <div class="flex items-start gap-4">
                        <div
                            :class="currentStatus >= status.id
                                ? 'bg-primary-600 text-white'
                                : 'bg-gray-200 dark:bg-dark-700 text-gray-400'"
                            class="w-10 h-10 rounded-full flex items-center justify-center text-lg flex-shrink-0 transition-all duration-500"
                            x-text="currentStatus > status.id ? '✓' : status.icon"
                        ></div>
                        <div class="flex-1 pt-1">
                            <div class="flex items-center justify-between">
                                <span
                                    :class="currentStatus >= status.id ? 'text-gray-900 dark:text-white font-semibold' : 'text-gray-400'"
                                    class="text-sm transition-all"
                                    x-text="status.label"
                                ></span>
                                <span class="text-xs text-gray-400" x-text="status.time"></span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="status.desc"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn btn-primary">
            Kembali ke Beranda
        </a>
    </div>

    {{-- Checkout Form --}}
    <div x-show="!orderPlaced" class="container-custom">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Order Type --}}
                <div class="card p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Tipe Pesanan</h3>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach([
                            ['value' => 'dine-in', 'icon' => '🍽️', 'label' => 'Dine In'],
                            ['value' => 'takeaway', 'icon' => '🥡', 'label' => 'Take Away'],
                            ['value' => 'delivery', 'icon' => '🛵', 'label' => 'Delivery'],
                        ] as $type)
                        <button
                            @click="orderType = '{{ $type['value'] }}'"
                            :class="orderType === '{{ $type['value'] }}'
                                ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30'
                                : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'"
                            class="p-4 rounded-xl border-2 text-center transition-all duration-200"
                        >
                            <div class="text-2xl mb-1">{{ $type['icon'] }}</div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $type['label'] }}</div>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Customer Info --}}
                <div class="card p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Informasi Pemesan</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap *</label>
                                <input x-model="form.name" type="text" placeholder="Nama kamu" class="input">
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

                        {{-- Dine In: Table Number --}}
                        <div x-show="orderType === 'dine-in'">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nomor Meja</label>
                            <input x-model="form.tableNumber" type="text" placeholder="Contoh: Meja 5" class="input">
                        </div>

                        {{-- Delivery: Address --}}
                        <div x-show="orderType === 'delivery'">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Pengiriman *</label>
                            <textarea x-model="form.address" rows="3" placeholder="Masukkan alamat lengkap..." class="input resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan Pesanan</label>
                            <textarea x-model="form.notes" rows="2" placeholder="Contoh: tidak pedas, tanpa bawang, dll." class="input resize-none"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="card p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Metode Pembayaran</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">QRIS</div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                    :class="paymentMethod === 'qris-dana' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="qris-dana" class="hidden">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">💙</span>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">DANA</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Bayar lewat DANA</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                    :class="paymentMethod === 'qris-ovo' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="qris-ovo" class="hidden">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">💜</span>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">OVO</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Bayar lewat OVO</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                    :class="paymentMethod === 'qris-gopay' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="qris-gopay" class="hidden">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">💚</span>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">GoPay</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Bayar lewat GoPay</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Virtual Account</div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach([
                                    ['value' => 'va-bca', 'name' => 'BCA'],
                                    ['value' => 'va-bri', 'name' => 'BRI'],
                                    ['value' => 'va-bni', 'name' => 'BNI'],
                                    ['value' => 'va-mandiri', 'name' => 'Mandiri'],
                                    ['value' => 'va-cimb', 'name' => 'CIMB Niaga'],
                                    ['value' => 'va-permata', 'name' => 'Permata'],
                                    ['value' => 'va-danamon', 'name' => 'Danamon'],
                                ] as $payment)
                                <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                    :class="paymentMethod === '{{ $payment['value'] }}' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="{{ $payment['value'] }}" class="hidden">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $payment['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">VA</div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Transfer Bank Manual</div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach([
                                    ['value' => 'manual-bca', 'name' => 'BCA'],
                                    ['value' => 'manual-bri', 'name' => 'BRI'],
                                    ['value' => 'manual-bni', 'name' => 'BNI'],
                                    ['value' => 'manual-mandiri', 'name' => 'Mandiri'],
                                    ['value' => 'manual-bsi', 'name' => 'BSI'],
                                    ['value' => 'manual-permata', 'name' => 'Permata'],
                                    ['value' => 'manual-cimb', 'name' => 'CIMB Niaga'],
                                ] as $payment)
                                <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                    :class="paymentMethod === '{{ $payment['value'] }}' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="{{ $payment['value'] }}" class="hidden">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $payment['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manual</div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Credit / Debit Card</div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach([
                                    ['value' => 'card-visa', 'name' => 'Visa'],
                                    ['value' => 'card-mastercard', 'name' => 'Mastercard'],
                                    ['value' => 'card-jcb', 'name' => 'JCB'],
                                ] as $payment)
                                <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                    :class="paymentMethod === '{{ $payment['value'] }}' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="{{ $payment['value'] }}" class="hidden">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $payment['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kartu</div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">PayLater</div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach([
                                    ['value' => 'paylater-shopee', 'name' => 'Shopee PayLater'],
                                    ['value' => 'paylater-kredivo', 'name' => 'Kredivo'],
                                    ['value' => 'paylater-akulaku', 'name' => 'Akulaku'],
                                ] as $payment)
                                <label class="p-4 rounded-2xl border cursor-pointer transition-all duration-200"
                                    :class="paymentMethod === '{{ $payment['value'] }}' ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/30' : 'border-gray-200 dark:border-dark-600 hover:border-primary-400'">
                                    <input type="radio" x-model="paymentMethod" value="{{ $payment['value'] }}" class="hidden">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $payment['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bayar Nanti</div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-3xl border border-dashed border-gray-200 dark:border-dark-700 bg-gray-50 dark:bg-dark-800 p-4 text-sm text-gray-600 dark:text-gray-300 space-y-3">
                            <div x-show="paymentMethod === 'cash'">Bayar langsung ketika pesanan siap diambil atau dikirim.</div>
                            <div x-show="paymentMethod.startsWith('qris-')">Scan QRIS menggunakan aplikasi DANA, OVO, atau GoPay, lalu konfirmasi pembayaran.</div>
                            <div x-show="paymentMethod.startsWith('va-')">
                                <div class="font-semibold text-gray-900 dark:text-white" x-text="paymentLabel()"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="paymentAccountNumber()"></div>
                                <div class="mt-2">Bayar melalui mobile banking atau internet banking dengan nomor Virtual Account di atas.</div>
                            </div>
                            <div x-show="paymentMethod.startsWith('manual-')">
                                <div class="font-semibold text-gray-900 dark:text-white" x-text="paymentLabel()"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="paymentAccountNumber()"></div>
                                <div class="mt-2">Unggah bukti transfer setelah melakukan pembayaran untuk mempercepat verifikasi.</div>
                                <input type="file" @change="proofFileName = $event.target.files[0]?.name || ''" class="input text-sm py-2 w-full">
                                <p x-show="proofFileName" class="text-xs text-green-600">Bukti transfer siap: <span x-text="proofFileName"></span></p>
                            </div>
                            <div x-show="paymentMethod.startsWith('card-')">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Masukkan informasi kartu kredit/debit untuk pembayaran cepat dan aman.</div>
                                <input x-model="form.cardNumber" type="text" placeholder="Nomor kartu" class="input text-sm py-2 w-full">
                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <input x-model="form.cardExpiry" type="text" placeholder="MM/YY" class="input text-sm py-2 w-full">
                                    <input x-model="form.cardCvv" type="text" placeholder="CVV" class="input text-sm py-2 w-full">
                                </div>
                            </div>
                            <div x-show="paymentMethod.startsWith('paylater-')">
                                <div class="font-semibold text-gray-900 dark:text-white" x-text="paymentLabel()"></div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Bayar nanti dengan metode PayLater pilihanmu.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Ringkasan Pesanan</h3>

                    {{-- Empty Cart --}}
                    <div x-show="$store.cart.items.length === 0" class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-dark-700 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Keranjang kosong</p>
                        <a href="{{ route('menu') }}" class="btn btn-primary mt-4 text-sm py-2">Pilih Menu</a>
                    </div>

                    {{-- Cart Items --}}
                    <div x-show="$store.cart.items.length > 0">
                        <div class="space-y-3 mb-4 max-h-64 overflow-y-auto scrollbar-hide">
                            <template x-for="item in $store.cart.items" :key="item.id">
                                <div class="flex items-center gap-3">
                                    <img :src="item.image" :alt="item.name" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="item.name"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400" x-text="`${item.qty}x ${formatPrice(item.price)}`"></div>
                                    </div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white" x-text="formatPrice(item.price * item.qty)"></div>
                                </div>
                            </template>
                        </div>

                        {{-- Voucher --}}
                        <div class="mb-4">
                            <div class="flex gap-2">
                                <input
                                    x-model="voucherCode"
                                    type="text"
                                    placeholder="Kode voucher"
                                    class="input text-sm py-2 flex-1"
                                    @keydown.enter="applyVoucher()"
                                >
                                <button @click="applyVoucher()" class="btn btn-outline text-sm py-2 px-3">Pakai</button>
                            </div>
                            <p x-show="voucherMessage"
                                :class="voucherSuccess ? 'text-green-600' : 'text-red-500'"
                                class="text-xs mt-1"
                                x-text="voucherMessage">
                            </p>
                        </div>

                        {{-- Totals --}}
                        <div class="space-y-2 text-sm border-t border-gray-200 dark:border-dark-700 pt-4 mb-4">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span x-text="formatPrice($store.cart.subtotal)"></span>
                            </div>
                            <div x-show="orderType === 'delivery'" class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Ongkir</span>
                                <span>Rp 10.000</span>
                            </div>
                            <div x-show="$store.cart.discount > 0" class="flex justify-between text-green-600">
                                <span>Diskon</span>
                                <span x-text="'- ' + formatPrice($store.cart.discount)"></span>
                            </div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Metode Pembayaran</span>
                                <span x-text="paymentLabel()"></span>
                            </div>
                            <div class="flex justify-between font-bold text-base text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-dark-700">
                                <span>Total</span>
                                <span x-text="formatPrice($store.cart.total + (orderType === 'delivery' ? 10000 : 0))"></span>
                            </div>
                        </div>

                        {{-- Place Order --}}
                        <button
                            @click="placeOrder()"
                            :disabled="isProcessing || !form.name || !form.phone || (paymentMethod.startsWith('manual-') && !proofFileName) || (paymentMethod.startsWith('card-') && (!form.cardNumber || !form.cardExpiry || !form.cardCvv))"
                            :class="isProcessing || !form.name || !form.phone || (paymentMethod.startsWith('manual-') && !proofFileName) || (paymentMethod.startsWith('card-') && (!form.cardNumber || !form.cardExpiry || !form.cardCvv)) ? 'opacity-60 cursor-not-allowed' : ''"
                            class="btn btn-primary w-full"
                        >
                            <span x-show="!isProcessing">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Pesan Sekarang
                            </span>
                            <span x-show="isProcessing" class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                        <p x-show="paymentMethod.startsWith('manual-') && !proofFileName" class="text-xs text-red-500 mt-3">Unggah bukti transfer agar pesanan dapat segera diverifikasi.</p>
                        <p x-show="paymentMethod.startsWith('card-') && (!form.cardNumber || !form.cardExpiry || !form.cardCvv)" class="text-xs text-red-500 mt-3">Masukkan nomor kartu, tanggal kadaluarsa, dan CVV untuk melanjutkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
