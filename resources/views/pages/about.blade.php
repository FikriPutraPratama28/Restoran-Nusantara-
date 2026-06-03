@extends('layouts.app')

@section('title', 'Tentang Kami — Restoran NUSANTARA')

@section('content')

<div class="pt-24 pb-10 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-dark-900 dark:to-dark-800">
    <div class="container-custom text-center">
        <span class="badge badge-primary mb-3">Tentang Kami</span>
        <h1 class="font-display text-4xl md:text-6xl font-bold text-white mb-4">
            Cerita <span class="gradient-text">Kami</span>
        </h1>
        <p class="text-gray-400 max-w-xl mx-auto">
            Dari dapur keluarga ke restoran digital modern
        </p>
    </div>
</div>

<section class="section">
    <div class="container-custom">

        {{-- Story --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <div x-data x-intersect="$el.classList.add('animate-slide-up')">
                <span class="badge badge-primary mb-4">Kisah Kami</span>
                <h2 class="font-display text-4xl font-bold text-gray-900 dark:text-white mb-6">
                    Berawal dari Cinta <br>terhadap Kuliner
                </h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
                    Restoran NUSANTARA didirikan pada tahun 2019 dengan satu misi sederhana: menyajikan cita rasa autentik masakan Nusantara dengan sentuhan modern yang memudahkan semua orang menikmatinya.
                </p>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                    Dimulai dari warung kecil di sudut kota, kini kami telah berkembang menjadi restoran digital yang melayani ribuan pelanggan setiap harinya. Kami percaya bahwa teknologi dan kuliner bisa berjalan beriringan.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['value' => '2019', 'label' => 'Tahun Berdiri'],
                        ['value' => '10K+', 'label' => 'Pelanggan Puas'],
                        ['value' => '500+', 'label' => 'Menu Tersedia'],
                        ['value' => '4.9 / 5.0', 'label' => 'Rating Google'],
                    ] as $stat)
                    <div class="bg-gray-50 dark:bg-dark-800 rounded-xl p-4">
                        <div class="text-2xl font-bold text-primary-600">{{ $stat['value'] }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative" x-data x-intersect="$el.classList.add('animate-fade-in')">
                <img
                    src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=500&fit=crop"
                    alt="Restaurant"
                    class="rounded-2xl shadow-2xl w-full"
                >
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
        <div class="text-center mb-12">
            <h2 class="font-display text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Nilai-Nilai <span class="gradient-text">Kami</span>
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
            @php
            $values = [
                [
                    'title' => 'Passion',
                    'desc' => 'Kami memasak dengan penuh cinta dan dedikasi untuk setiap hidangan yang kami sajikan',
                    'svg' => '<svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
                ],
                [
                    'title' => 'Kualitas',
                    'desc' => 'Bahan-bahan segar pilihan terbaik dipilih setiap hari untuk memastikan kualitas terjaga',
                    'svg' => '<svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>',
                ],
                [
                    'title' => 'Kepercayaan',
                    'desc' => 'Membangun kepercayaan pelanggan adalah prioritas utama kami dalam setiap pelayanan',
                    'svg' => '<svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                ],
            ];
            @endphp
            @foreach($values as $value)
            <div class="card p-6 text-center card-hover" x-data x-intersect="$el.classList.add('animate-slide-up')">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center mx-auto mb-4">{!! $value['svg'] !!}</div>
                <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-3">{{ $value['title'] }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $value['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Team --}}
        <div class="text-center mb-12">
            <h2 class="font-display text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Tim <span class="gradient-text">Kami</span>
            </h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($team as $m)
            <div class="card p-6 text-center card-hover" x-data x-intersect="$el.classList.add('animate-slide-up')">
                @if($m->image_src)
                    <img src="{{ $m->image_src }}" alt="{{ $m->name }}" class="w-20 h-20 rounded-2xl object-cover mx-auto mb-4 shadow-lg">
                @else
                    <div class="w-20 h-20 bg-gradient-to-br {{ $m->gradient }} rounded-2xl flex items-center justify-center text-white text-2xl font-black mx-auto mb-4 shadow-lg">
                        {{ $m->emoji ?: strtoupper(substr($m->name, 0, 2)) }}
                    </div>
                @endif
                <h3 class="font-bold text-gray-900 dark:text-white">{{ $m->name }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $m->role }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>

@endsection
