<footer class="bg-gray-900 dark:bg-dark-900 text-gray-300">
    <div class="container-custom py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    @php $logoUrl = \App\Models\SiteSetting::logoUrl(); @endphp
                    <div class="w-11 h-11 flex-shrink-0">
                        @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full rounded-xl object-cover">
                        @else
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-lg">
                            <rect width="48" height="48" rx="12" fill="url(#footerLogoGrad)"/>
                            <path d="M4 4 L10 4 L4 10 Z" fill="white" fill-opacity="0.15"/>
                            <path d="M44 4 L38 4 L44 10 Z" fill="white" fill-opacity="0.15"/>
                            <path d="M4 44 L10 44 L4 38 Z" fill="white" fill-opacity="0.15"/>
                            <path d="M44 44 L38 44 L44 38 Z" fill="white" fill-opacity="0.15"/>
                            <circle cx="24" cy="24" r="13" fill="white" fill-opacity="0.95"/>
                            <circle cx="24" cy="24" r="10" fill="none" stroke="#f97316" stroke-width="1.2" stroke-opacity="0.4"/>
                            <path d="M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13 C19.5 12.5 18.5 12.5 18 13Z" fill="#ea580c"/>
                            <path d="M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18" stroke="#ea580c" stroke-width="1.3" stroke-linecap="round" fill="none"/>
                            <circle cx="24" cy="24" r="1.5" fill="#f97316" fill-opacity="0.7"/>
                            <defs>
                                <linearGradient id="footerLogoGrad" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#ea580c"/>
                                    <stop offset="50%" stop-color="#f97316"/>
                                    <stop offset="100%" stop-color="#c2410c"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        @endif
                    </div>
                    <div class="leading-none">
                        <div class="font-display font-bold text-lg text-white leading-tight">{{ $_site['restaurant_name'] ?? 'Restoran' }}</div>
                        <div class="font-bold tracking-widest text-[11px] uppercase leading-tight" style="color:#f97316; font-family:'Playfair Display',serif;">{{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }}</div>
                    </div>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">
                    {{ $_site['description'] ?? 'Pengalaman kuliner modern dengan cita rasa autentik Nusantara.' }}
                </p>
                <div class="flex gap-3">
                    @foreach(['instagram', 'facebook', 'twitter', 'tiktok'] as $social)
                    <a href="#" class="w-9 h-9 bg-gray-800 dark:bg-dark-700 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all duration-200">
                        @if($social === 'instagram')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @elseif($social === 'facebook')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        @elseif($social === 'twitter')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.27 8.27 0 004.84 1.55V6.79a4.85 4.85 0 01-1.07-.1z"/></svg>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-bold mb-4">Menu Cepat</h4>
                <ul class="space-y-2 text-sm">
                    @foreach([
                        ['route' => 'home', 'label' => 'Beranda'],
                        ['route' => 'menu', 'label' => 'Menu Kami'],
                        ['route' => 'reservation', 'label' => 'Reservasi Meja'],
                        ['route' => 'promo', 'label' => 'Promo & Diskon'],
                        ['route' => 'about', 'label' => 'Tentang Kami'],
                        ['route' => 'contact', 'label' => 'Hubungi Kami'],
                    ] as $link)
                    <li>
                        <a href="{{ route($link['route']) }}" class="hover:text-primary-400 transition-colors">
                            {{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Info --}}
            <div>
                <h4 class="text-white font-bold mb-4">Informasi</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <div class="w-5 h-5 text-primary-400 mt-0.5 flex-shrink-0">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span>Jl. Kuliner Nusantara No. 88, Jakarta Selatan, 12345</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-5 h-5 text-primary-400 flex-shrink-0">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <a href="tel:+6281234567890" class="hover:text-primary-400 transition-colors">+62 812-3456-7890</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-5 h-5 text-primary-400 flex-shrink-0">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <a href="mailto:hello@warungnusantara.id" class="hover:text-primary-400 transition-colors">hello@warungnusantara.id</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <div class="w-5 h-5 text-primary-400 mt-0.5 flex-shrink-0">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div>Senin – Jumat: 10.00 – 22.00</div>
                            <div>Sabtu – Minggu: 09.00 – 23.00</div>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- App Download --}}
            <div>
                <h4 class="text-white font-bold mb-4">Download App</h4>
                <p class="text-sm text-gray-400 mb-4">Dapatkan pengalaman terbaik dengan aplikasi kami.</p>
                <div class="space-y-3">
                    <a href="#" class="flex items-center gap-3 bg-gray-800 dark:bg-dark-700 hover:bg-gray-700 rounded-xl px-4 py-3 transition-all">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                        <div>
                            <div class="text-xs text-gray-400">Download di</div>
                            <div class="text-sm font-semibold text-white">App Store</div>
                        </div>
                    </a>
                    <a href="#" class="flex items-center gap-3 bg-gray-800 dark:bg-dark-700 hover:bg-gray-700 rounded-xl px-4 py-3 transition-all">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 20.5v-17c0-.83.94-1.3 1.6-.8l14 8.5c.6.36.6 1.24 0 1.6l-14 8.5c-.66.5-1.6.03-1.6-.8z"/></svg>
                        <div>
                            <div class="text-xs text-gray-400">Download di</div>
                            <div class="text-sm font-semibold text-white">Google Play</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-gray-800 dark:border-dark-700">
        <div class="container-custom py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-gray-500">
            <span>© {{ date('Y') }} Restoran NUSANTARA. All rights reserved.</span>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-primary-400 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-primary-400 transition-colors">Syarat & Ketentuan</a>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-1.5 text-gray-600 hover:text-primary-400 transition-colors group">
                    <svg class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Admin
                </a>
            </div>
        </div>
    </div>
</footer>
