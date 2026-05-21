{{-- Cart Overlay & Sidebar --}}
<div x-data x-cloak>
    {{-- Backdrop --}}
    <div
        x-show="$store.cart.isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.cart.close()"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]"
    ></div>

    {{-- Sidebar --}}
    <div
        x-show="$store.cart.isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed right-0 top-0 bottom-0 w-full max-w-md bg-white dark:bg-dark-800 z-[70] flex flex-col shadow-2xl"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-dark-700">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Keranjang Saya</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="`${$store.cart.count} item`"></p>
            </div>
            <button
                @click="$store.cart.close()"
                class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-dark-600 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Empty State --}}
        <div
            x-show="$store.cart.items.length === 0"
            class="flex-1 flex flex-col items-center justify-center gap-4 p-8 text-center"
        >
            <div class="text-7xl animate-float">🛒</div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Keranjang Kosong</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Yuk, tambahkan menu favoritmu!</p>
            <button
                @click="$store.cart.close()"
                onclick="window.location.href='{{ route('menu') }}'"
                class="btn btn-primary"
            >
                Lihat Menu
            </button>
        </div>

        {{-- Cart Items --}}
        <div
            x-show="$store.cart.items.length > 0"
            class="flex-1 overflow-y-auto p-4 space-y-3"
        >
            <template x-for="item in $store.cart.items" :key="item.id">
                <div class="flex gap-3 p-3 bg-gray-50 dark:bg-dark-700 rounded-xl animate-fade-in">
                    <img
                        :src="item.image"
                        :alt="item.name"
                        class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
                    >
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white truncate" x-text="item.name"></h4>
                        <p class="text-primary-600 font-bold text-sm mt-1"
                            x-text="new Intl.NumberFormat('id-ID', {style:'currency',currency:'IDR',minimumFractionDigits:0}).format(item.price)">
                        </p>
                        <div class="flex items-center gap-2 mt-2">
                            <button
                                @click="$store.cart.updateQty(item.id, item.qty - 1)"
                                class="w-7 h-7 rounded-lg bg-gray-200 dark:bg-dark-600 flex items-center justify-center hover:bg-primary-100 dark:hover:bg-primary-900 transition-all text-sm font-bold"
                            >−</button>
                            <span class="w-6 text-center font-bold text-sm" x-text="item.qty"></span>
                            <button
                                @click="$store.cart.updateQty(item.id, item.qty + 1)"
                                class="w-7 h-7 rounded-lg bg-gray-200 dark:bg-dark-600 flex items-center justify-center hover:bg-primary-100 dark:hover:bg-primary-900 transition-all text-sm font-bold"
                            >+</button>
                        </div>
                    </div>
                    <div class="flex flex-col items-end justify-between">
                        <button
                            @click="$store.cart.remove(item.id)"
                            class="text-gray-400 hover:text-red-500 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        <span class="text-sm font-bold text-gray-900 dark:text-white"
                            x-text="new Intl.NumberFormat('id-ID', {style:'currency',currency:'IDR',minimumFractionDigits:0}).format(item.price * item.qty)">
                        </span>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div
            x-show="$store.cart.items.length > 0"
            class="p-4 border-t border-gray-200 dark:border-dark-700 space-y-4"
        >
            {{-- Voucher --}}
            <div x-data="{ voucherCode: '', msg: '', success: false }" class="flex gap-2">
                <input
                    x-model="voucherCode"
                    type="text"
                    placeholder="Kode voucher..."
                    class="input text-sm py-2 flex-1"
                    @keydown.enter="
                        const r = $store.cart.applyVoucher(voucherCode);
                        msg = r.message; success = r.success;
                    "
                >
                <button
                    @click="
                        const r = $store.cart.applyVoucher(voucherCode);
                        msg = r.message; success = r.success;
                    "
                    class="btn btn-outline text-sm py-2 px-4"
                >
                    Pakai
                </button>
            </div>

            {{-- Summary --}}
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Subtotal</span>
                    <span x-text="new Intl.NumberFormat('id-ID', {style:'currency',currency:'IDR',minimumFractionDigits:0}).format($store.cart.subtotal)"></span>
                </div>
                <div x-show="$store.cart.discount > 0" class="flex justify-between text-green-600">
                    <span>Diskon</span>
                    <span x-text="'- ' + new Intl.NumberFormat('id-ID', {style:'currency',currency:'IDR',minimumFractionDigits:0}).format($store.cart.discount)"></span>
                </div>
                <div class="flex justify-between font-bold text-base text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-dark-700">
                    <span>Total</span>
                    <span x-text="new Intl.NumberFormat('id-ID', {style:'currency',currency:'IDR',minimumFractionDigits:0}).format($store.cart.total)"></span>
                </div>
            </div>

            {{-- Checkout Button --}}
            <a
                href="{{ route('checkout') }}"
                @click="$store.cart.close()"
                class="btn btn-primary w-full text-center"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
                Checkout Sekarang
            </a>
        </div>
    </div>
</div>
