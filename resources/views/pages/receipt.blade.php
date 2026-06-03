@extends('layouts.app')

@section('title', 'Detail Reservasi #' . $reservation->reservation_code . ' — Warung Nusantara')

@section('content')
<style>
    @media print {
        /* Sembunyikan elemen non-print */
        header, footer, nav, .no-print, button, a.btn {
            display: none !important;
        }
        /* Hilangkan padding & background */
        body {
            background-color: white !important;
            color: black !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .main-content {
            padding-top: 0 !important;
        }
        /* Sempurnakan tampilan kartu struk untuk cetak */
        .print-receipt {
            border: 1px solid #e2e8f0 !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
            border-radius: 0 !important;
        }
    }
</style>

{{-- Header --}}
<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800 no-print">
    <div class="container-custom text-center">
        <span class="badge badge-success mb-3">✓ Reservasi Terbuat</span>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-4">
            Struk <span class="gradient-text">Reservasi</span>
        </h1>
        <p class="text-gray-400 max-w-xl mx-auto">
            Simpan atau cetak bukti reservasi Anda di bawah ini
        </p>
    </div>
</div>

<section class="section bg-gray-50 dark:bg-dark-900 min-h-screen main-content">
    <div class="container-custom">
        <div class="max-w-2xl mx-auto">

            {{-- Struk Card --}}
            <div class="bg-white dark:bg-dark-800 rounded-3xl border border-gray-100 dark:border-dark-700 shadow-xl overflow-hidden print-receipt">
                {{-- Logo and Receipt Header --}}
                <div class="bg-gradient-to-r from-primary-600 to-orange-500 p-6 text-center text-white no-print">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h2 class="text-xl font-bold mt-2">Restoran Nusantara</h2>
                    <p class="text-xs text-white/80">Cita Rasa Otentik Nusantara</p>
                </div>

                {{-- Print-only Restaurant Info --}}
                <div class="hidden print:block text-center border-b border-dashed border-gray-300 pb-6 mb-6">
                    <h2 class="text-2xl font-bold">Restoran Nusantara</h2>
                    <p class="text-xs text-gray-500">Cita Rasa Otentik Nusantara</p>
                    <p class="text-xs text-gray-500">Jl. Nusantara Raya No. 45, Jakarta</p>
                    <p class="text-xs text-gray-500">Telp: +62 812-3456-7890</p>
                </div>

                <div class="p-8">
                    {{-- Status Banner --}}
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Reservasi Berhasil Dibuat!</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Simpan halaman ini atau cetak struk sebagai bukti kedatangan.</p>
                    </div>

                    {{-- Code & Status Grid --}}
                    <div class="grid grid-cols-2 gap-4 border-b border-gray-100 dark:border-dark-700 pb-6 mb-6 text-sm">
                        <div>
                            <span class="text-gray-400 block text-xs uppercase font-semibold">Kode Reservasi</span>
                            <span class="font-mono text-lg font-bold text-primary-600">{{ $reservation->reservation_code }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-400 block text-xs uppercase font-semibold">Status</span>
                            @php
                                $statusLabels = [
                                    'pending' => ['text' => 'Menunggu Konfirmasi', 'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950 dark:text-yellow-400'],
                                    'confirmed' => ['text' => 'Dikonfirmasi', 'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-400'],
                                    'completed' => ['text' => 'Selesai', 'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-400'],
                                    'cancelled' => ['text' => 'Dibatalkan', 'class' => 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-400']
                                ];
                                $status = $statusLabels[$reservation->status] ?? ['text' => ucfirst($reservation->status), 'class' => 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Details Section --}}
                    <h4 class="text-xs uppercase tracking-wider font-semibold text-gray-400 mb-4">Informasi Reservasi</h4>
                    <div class="space-y-4 border-b border-gray-100 dark:border-dark-700 pb-6 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block text-xs">Tanggal</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->reservation_date->format('d F Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block text-xs">Waktu Kedatangan</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ date('H:i', strtotime($reservation->reservation_time)) }} WIB</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block text-xs">Jumlah Tamu</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->number_of_guests }} Orang</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block text-xs">Meja &amp; Area</span>
                                <span class="font-semibold text-gray-900 dark:text-white capitalize">
                                    {{ $reservation->table_number ?? '-' }} ({{ $reservation->table_area }})
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Details Section --}}
                    <h4 class="text-xs uppercase tracking-wider font-semibold text-gray-400 mb-4">Detail Pemesan</h4>
                    <div class="space-y-4 border-b border-gray-100 dark:border-dark-700 pb-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block text-xs">Nama Lengkap</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->customer_name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400 block text-xs">Nomor Telepon</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->customer_phone }}</span>
                            </div>
                        </div>

                        @if($reservation->customer_email)
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block text-xs">Email</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $reservation->customer_email }}</span>
                        </div>
                        @endif

                        @if($reservation->notes)
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block text-xs">Catatan Khusus</span>
                            <span class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $reservation->notes }}"</span>
                        </div>
                        @endif
                    </div>

                    {{-- Ordered Items Section --}}
                    @if(!empty($reservation->ordered_items))
                    <h4 class="text-xs uppercase tracking-wider font-semibold text-gray-400 mb-4">Menu Yang Dipesan</h4>
                    <div class="space-y-3 border-b border-gray-100 dark:border-dark-700 pb-6 mb-6">
                        @php $grandTotal = 0; @endphp
                        @foreach($reservation->ordered_items as $item)
                            @php
                                $itemPrice = $item['price'] ?? 0;
                                $itemQty = $item['qty'] ?? 1;
                                $subtotal = $itemPrice * $itemQty;
                                $grandTotal += $subtotal;
                            @endphp
                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center gap-3">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="" class="w-10 h-10 rounded-lg object-cover no-print flex-shrink-0">
                                    @endif
                                    <div>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item['name'] }}</span>
                                        <span class="text-xs text-gray-400 block">Rp {{ number_format($itemPrice, 0, ',', '.') }} x {{ $itemQty }}</span>
                                    </div>
                                </div>
                                <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div class="border-t border-dashed border-gray-200 dark:border-dark-600 pt-3 mt-3 flex justify-between items-center font-bold text-base text-gray-950 dark:text-white">
                            <span>Total Menu</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Payment Details Section --}}
                    <h4 class="text-xs uppercase tracking-wider font-semibold text-gray-400 mb-4">Informasi Pembayaran</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Metode Pembayaran</span>
                            @php
                                $paymentLabels = [
                                    'cash' => 'Tunai (di Restoran)',
                                    'qris' => 'QRIS E-Wallet',
                                    'va' => 'Virtual Account Bank'
                                ];
                                $method = $paymentLabels[$reservation->payment_method] ?? ucfirst($reservation->payment_method ?? 'Tunai');
                            @endphp
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $method }}</span>
                        </div>
                    </div>
                </div>

                {{-- Struk Footer info --}}
                <div class="bg-gray-50 dark:bg-dark-750 px-8 py-6 border-t border-gray-100 dark:border-dark-700 text-center text-xs text-gray-400 print:bg-white print:text-black">
                    <p class="mb-1">Silakan tunjukkan struk ini kepada staf penerima tamu kami saat kedatangan.</p>
                    <p>Waktu Pembuatan: {{ $reservation->created_at->format('d/m/Y H:i') }} WIB</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center no-print">
                <button onclick="window.print()" class="btn btn-primary flex-1 flex items-center justify-center gap-2 py-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18h12v4H6v-4zm0-7h12a2 2 0 012 2v4H4v-4a2 2 0 012-2z"/></svg>
                    Cetak Struk
                </button>
                <a href="{{ route('reservation') }}" class="btn btn-secondary flex-1 flex items-center justify-center gap-2 py-3">
                    Buat Reservasi Baru
                </a>
            </div>

            <div class="text-center mt-6 no-print">
                <a href="{{ route('home') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 transition-colors">
                    ← Kembali ke Halaman Utama
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
