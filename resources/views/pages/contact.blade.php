@extends('layouts.app')

@section('title', 'Kontak — Warung Nusantara')

@section('content')

<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-primary mb-3">Hubungi Kami</span>
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
                <div class="card p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Alamat</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Jl. Kuliner Nusantara No. 88</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Jakarta Selatan, 12345</p>
                    </div>
                </div>
                <div class="card p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Telepon</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">+62 812-3456-7890</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">+62 21-1234-5678</p>
                    </div>
                </div>
                <div class="card p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Email</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">hello@warungnusantara.id</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">support@warungnusantara.id</p>
                    </div>
                </div>
                <div class="card p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Jam Operasional</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Senin – Jumat: 10.00 – 22.00</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Sabtu – Minggu: 09.00 – 23.00</p>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="card p-8" x-data="{ submitted: false, form: { name: '', email: '', subject: '', message: '' } }">

                    <div x-show="submitted" class="text-center py-12 animate-fade-in">
                        <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
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
                    <div class="w-16 h-16 bg-gray-300 dark:bg-dark-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Google Maps</p>
                    <p class="text-gray-500 dark:text-gray-500 text-sm">Jl. Kuliner Nusantara No. 88, Jakarta Selatan</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
