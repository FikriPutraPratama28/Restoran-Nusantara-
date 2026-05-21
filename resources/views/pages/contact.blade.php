@extends('layouts.app')

@section('title', 'Kontak — Warung Nusantara')

@section('content')

<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-primary mb-3">📞 Hubungi Kami</span>
        <h1 class="font-display text-4xl md:text-6xl font-bold text-white mb-4">
            Ada <span class="gradient-text">Pertanyaan?</span>
        </h1>
        <p class="text-gray-400 max-w-xl mx-auto">
            Kami siap membantu kamu 7 hari seminggu
        </p>
    </div>
</div>

<section class="section bg-gray-50 dark:bg-dark-900">
    <div class="container-custom">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Contact Info --}}
            <div class="space-y-4">
                @foreach([
                    ['icon' => '📍', 'title' => 'Alamat', 'lines' => ['Jl. Kuliner Nusantara No. 88', 'Jakarta Selatan, 12345']],
                    ['icon' => '📞', 'title' => 'Telepon', 'lines' => ['+62 812-3456-7890', '+62 21-1234-5678']],
                    ['icon' => '✉️', 'title' => 'Email', 'lines' => ['hello@warungnusantara.id', 'support@warungnusantara.id']],
                    ['icon' => '🕐', 'title' => 'Jam Operasional', 'lines' => ['Senin – Jumat: 10.00 – 22.00', 'Sabtu – Minggu: 09.00 – 23.00']],
                ] as $info)
                <div class="card p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                        {{ $info['icon'] }}
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

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="card p-8" x-data="{ submitted: false, form: { name: '', email: '', subject: '', message: '' } }">

                    <div x-show="submitted" class="text-center py-12 animate-fade-in">
                        <div class="text-6xl mb-4">✅</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pesan Terkirim!</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Kami akan membalas dalam 1x24 jam</p>
                        <button @click="submitted = false; form = { name: '', email: '', subject: '', message: '' }" class="btn btn-primary">
                            Kirim Pesan Lain
                        </button>
                    </div>

                    <div x-show="!submitted">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Kirim Pesan</h2>
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
                                <textarea
                                    x-model="form.message"
                                    rows="5"
                                    placeholder="Tulis pesanmu di sini..."
                                    class="input resize-none"
                                ></textarea>
                            </div>
                            <button
                                @click="if(form.name && form.email && form.message) submitted = true"
                                :disabled="!form.name || !form.email || !form.message"
                                :class="!form.name || !form.email || !form.message ? 'opacity-50 cursor-not-allowed' : ''"
                                class="btn btn-primary w-full"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim Pesan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Map Placeholder --}}
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

@endsection
