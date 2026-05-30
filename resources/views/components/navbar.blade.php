<nav
    x-data="{
        mobileOpen: false,
        scrolled: false,
        scrollProgress: 0,
        activeSection: 'home',
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 20;
                const dh = document.documentElement.scrollHeight - window.innerHeight;
                this.scrollProgress = dh > 0 ? (window.scrollY / dh) * 100 : 0;
            });
            const ids = ['home','menu','reservasi','promo','galeri','fasilitas','tentang','kontak'];
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) this.activeSection = e.target.id; });
            }, { rootMargin: '-25% 0px -65% 0px' });
            ids.forEach(id => { const el = document.getElementById(id); if (el) obs.observe(el); });
        },
        scrollTo(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const navH = document.querySelector('nav').offsetHeight;
            window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - navH, behavior: 'smooth' });
            this.mobileOpen = false;
        }
    }"
    :class="scrolled ? 'bg-white/95 dark:bg-dark-900/95 backdrop-blur-lg shadow-lg' : 'bg-transparent'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    {{-- Scroll Progress Bar --}}
    <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-primary-500 to-orange-400 transition-all duration-150 z-10"
        :style="`width: ${scrollProgress}%`"></div>

    <div class="container-custom">
        <div class="flex items-center justify-between h-16 md:h-18">

            {{-- Logo --}}
            <button @click="scrollTo('home')" class="flex items-center gap-2.5 group flex-shrink-0">
                @php $logoUrl = \App\Models\SiteSetting::logoUrl(); @endphp
                <div class="w-9 h-9 flex-shrink-0 relative group-hover:scale-110 transition-transform duration-300">
                    @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full rounded-xl object-cover">
                    @else
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-lg">
                        <rect width="48" height="48" rx="12" fill="url(#navLogoGrad)"/>
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
                            <linearGradient id="navLogoGrad" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#ea580c"/>
                                <stop offset="50%" stop-color="#f97316"/>
                                <stop offset="100%" stop-color="#c2410c"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    @endif
                </div>
                <div class="leading-none">
                    <div class="font-display font-bold text-base leading-tight"
                        :class="scrolled ? 'text-gray-900 dark:text-white' : 'text-white'">{{ $_site['restaurant_name'] ?? 'Restoran' }}</div>
                    <div class="font-bold tracking-widest uppercase text-[10px] leading-tight"
                        style="color: #f97316; font-family: 'Playfair Display', serif;">{{ $_site['restaurant_tagline'] ?? 'NUSANTARA' }}</div>
                </div>
            </button>

            {{-- Desktop Nav --}}
            <div class="hidden lg:flex items-center gap-0.5">
                @php
                $navLinks = [
                    ['id'=>'home',      'label'=>'Home'],
                    ['id'=>'menu',      'label'=>'Menu'],
                    ['id'=>'reservasi', 'label'=>'Reservasi'],
                    ['id'=>'promo',     'label'=>'Promo'],
                    ['id'=>'galeri',    'label'=>'Galeri'],
                    ['id'=>'fasilitas', 'label'=>'Fasilitas'],
                    ['id'=>'tentang',   'label'=>'Tentang'],
                    ['id'=>'kontak',    'label'=>'Kontak'],
                ];
                @endphp
                @foreach($navLinks as $link)
                <button @click="scrollTo('{{ $link['id'] }}')"
                    class="relative px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group"
                    :class="activeSection==='{{ $link['id'] }}'
                        ? 'text-primary-500'
                        : (scrolled ? 'text-gray-700 dark:text-gray-300 hover:text-primary-500' : 'text-white/90 hover:text-white')"
                >
                    <span class="absolute inset-0 rounded-lg transition-all duration-200"
                        :class="activeSection==='{{ $link['id'] }}' ? 'bg-primary-50 dark:bg-primary-900/30' : 'bg-transparent group-hover:bg-white/10 dark:group-hover:bg-dark-700'"></span>
                    <span class="relative z-10">{{ $link['label'] }}</span>
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 rounded-full bg-primary-500 transition-all duration-300"
                        :class="activeSection==='{{ $link['id'] }}' ? 'w-4/5' : 'w-0 group-hover:w-1/2'"></span>
                </button>
                @endforeach
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-1.5">
                {{-- QR Code --}}
                <div x-data="qrCode()">
                    <button @click="open()"
                        class="hidden md:flex w-9 h-9 rounded-xl items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95"
                        :class="scrolled ? 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-700' : 'text-white/80 hover:text-white hover:bg-white/10'"
                        title="QR Code Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </button>
                    {{-- QR Modal --}}
                    <div x-show="show" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
                        <div class="relative bg-white dark:bg-dark-800 rounded-2xl p-8 shadow-2xl text-center max-w-xs w-full z-10 animate-slide-up">
                            <button @click="close()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <div class="text-3xl mb-3">📱</div>
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1">Scan QR Menu</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mb-4">Scan untuk buka menu di HP kamu</p>
                            <div class="bg-white p-3 rounded-xl inline-block shadow-inner mb-4">
                                <img :src="qrSrc" alt="QR Code" class="w-44 h-44 rounded-lg">
                            </div>
                            <p class="text-xs text-gray-400 break-all" x-text="url"></p>
                        </div>
                    </div>
                </div>

                {{-- Dark Mode --}}
                <button @click="$store.theme.toggle()"
                    class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95"
                    :class="scrolled ? 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-700' : 'text-white/80 hover:text-white hover:bg-white/10'"
                    title="Dark Mode">
                    <span x-show="!$store.theme.dark" class="text-base">🌙</span>
                    <span x-show="$store.theme.dark" class="text-base">☀️</span>
                </button>

                {{-- Cart --}}
                <button @click="$store.cart.open()"
                    class="relative w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95"
                    :class="scrolled ? 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-700' : 'text-white/80 hover:text-white hover:bg-white/10'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span x-show="$store.cart.count > 0" x-text="$store.cart.count"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute -top-1 -right-1 w-5 h-5 bg-primary-600 text-white text-xs font-bold rounded-full flex items-center justify-center"></span>
                </button>

                {{-- CTA --}}
                <button @click="scrollTo('menu')" class="hidden md:flex btn btn-primary text-sm py-2 px-4 hover:scale-105 active:scale-95 transition-transform duration-200">
                    Order Sekarang
                </button>

                {{-- Auth Buttons / Profile Dropdown --}}
                @auth
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all duration-200 hover:scale-105"
                        :class="scrolled ? 'hover:bg-gray-100 dark:hover:bg-dark-700' : 'hover:bg-white/10'">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="w-8 h-8 rounded-xl object-cover border-2 border-white/30">
                        <div class="text-left hidden lg:block">
                            <p class="text-xs font-bold leading-tight"
                                :class="scrolled ? 'text-gray-800 dark:text-white' : 'text-white'">
                                {{ explode(' ', auth()->user()->name)[0] }}
                            </p>
                            <p class="text-[10px] leading-tight capitalize"
                                :class="scrolled ? 'text-gray-400 dark:text-slate-500' : 'text-white/60'">
                                {{ auth()->user()->role }}
                            </p>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform" :class="[profileOpen ? 'rotate-180' : '', scrolled ? 'text-gray-500 dark:text-slate-400' : 'text-white/70']"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="profileOpen" x-cloak
                        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden z-50">

                        {{-- User Info --}}
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
                            <p class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ auth()->user()->role === 'admin' ? 'bg-violet-100 text-violet-700' : (auth()->user()->role === 'karyawan' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>

                        {{-- Menu Items --}}
                        <div class="py-1">
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:text-violet-700 dark:hover:text-violet-400 transition-colors">
                                <span class="text-base">📊</span> Dashboard Admin
                            </a>
                            @endif
                            {{-- Karyawan links removed per request --}}
                            <a href="{{ route('profile') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                                <span class="text-base">👤</span> Profil Saya
                            </a>
                        </div>

                        {{-- Logout --}}
                        <div class="border-t border-gray-100 dark:border-slate-700 py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <span class="text-base">🚪</span> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                {{-- Guest: Login & Register --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-200 hover:scale-105"
                        :class="scrolled ? 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-dark-700' : 'text-white/90 hover:text-white hover:bg-white/10'">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="text-sm font-semibold px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white transition-all duration-200 hover:scale-105 backdrop-blur-sm"
                        :class="scrolled ? 'bg-violet-600 hover:bg-violet-700 text-white shadow-lg shadow-violet-600/30' : 'bg-white/20 hover:bg-white/30 text-white'">
                        Daftar
                    </a>
                </div>
                @endauth

                {{-- Mobile Toggle --}}
                <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200"
                    :class="scrolled ? 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-700' : 'text-white hover:bg-white/10'">
                    <div class="w-5 flex flex-col justify-center items-center gap-1">
                        <span :class="mobileOpen ? 'rotate-45 translate-y-1.5' : ''" class="block w-5 h-0.5 bg-current rounded transition-all duration-300"></span>
                        <span :class="mobileOpen ? 'opacity-0 scale-x-0' : ''" class="block w-5 h-0.5 bg-current rounded transition-all duration-300"></span>
                        <span :class="mobileOpen ? '-rotate-45 -translate-y-1.5' : ''" class="block w-5 h-0.5 bg-current rounded transition-all duration-300"></span>
                    </div>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-cloak
            x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 -translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-3"
            class="lg:hidden pb-4 border-t border-white/20 dark:border-dark-700 mt-2 pt-3">
            <div class="grid grid-cols-2 gap-1">
                @php
                $mobileLinks = [
                    ['id'=>'home',      'label'=>'Home',      'icon'=>'🏠'],
                    ['id'=>'menu',      'label'=>'Menu',       'icon'=>'🍽️'],
                    ['id'=>'reservasi', 'label'=>'Reservasi',  'icon'=>'📅'],
                    ['id'=>'promo',     'label'=>'Promo',      'icon'=>'🎁'],
                    ['id'=>'galeri',    'label'=>'Galeri',     'icon'=>'📸'],
                    ['id'=>'fasilitas', 'label'=>'Fasilitas',  'icon'=>'🏢'],
                    ['id'=>'tentang',   'label'=>'Tentang',    'icon'=>'ℹ️'],
                    ['id'=>'kontak',    'label'=>'Kontak',     'icon'=>'📞'],
                ];
                @endphp
                @foreach($mobileLinks as $link)
                <button @click="scrollTo('{{ $link['id'] }}')"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 text-left w-full"
                    :class="activeSection==='{{ $link['id'] }}' ? 'text-primary-500 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-700'">
                    <span>{{ $link['icon'] }}</span>
                    <span>{{ $link['label'] }}</span>
                    <span x-show="activeSection==='{{ $link['id'] }}'" class="ml-auto w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                </button>
                @endforeach
            </div>
            <div class="pt-3 border-t border-gray-200 dark:border-dark-700 mt-2 space-y-2">
                <button @click="scrollTo('menu')" class="btn btn-primary w-full text-sm">🛒 Order Sekarang</button>
                @auth
                <div class="space-y-1">
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 w-full py-2.5 px-3 rounded-xl bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-400 text-sm font-medium">
                        📊 Dashboard Admin
                    </a>
                    @endif
                    {{-- Karyawan mobile link removed per request --}}
                    <a href="{{ route('profile') }}" class="flex items-center gap-2 w-full py-2.5 px-3 rounded-xl border border-gray-200 dark:border-dark-700 text-gray-600 dark:text-gray-400 text-sm font-medium hover:bg-gray-50 dark:hover:bg-dark-700 transition-all">
                        👤 Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full py-2.5 px-3 rounded-xl border border-red-200 dark:border-red-900 text-red-600 dark:text-red-400 text-sm font-medium hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                            🚪 Logout
                        </button>
                    </form>
                </div>
                @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-gray-200 dark:border-dark-700 text-gray-600 dark:text-gray-400 text-sm font-medium hover:bg-gray-50 dark:hover:bg-dark-700 transition-all">
                    🔑 Masuk
                </a>
                <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition-all">
                    ✨ Daftar Sekarang
                </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
