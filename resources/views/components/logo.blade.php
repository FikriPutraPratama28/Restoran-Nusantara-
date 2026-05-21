{{--
    Logo Component — Restoran NUSANTARA
    Usage:
      @include('components.logo', ['size' => 'md', 'dark' => false])
    Sizes: sm | md | lg
--}}
@props(['size' => 'md', 'textColor' => null])

@php
$sizes = [
    'sm' => ['box' => 'w-8 h-8',  'text1' => 'text-sm',  'text2' => 'text-[9px]'],
    'md' => ['box' => 'w-10 h-10', 'text1' => 'text-base','text2' => 'text-[10px]'],
    'lg' => ['box' => 'w-14 h-14', 'text1' => 'text-xl',  'text2' => 'text-xs'],
];
$s = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="flex items-center gap-2.5">
    {{-- Icon: Piring dengan motif Nusantara --}}
    <div class="{{ $s['box'] }} flex-shrink-0 relative">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-lg">
            {{-- Background rounded square --}}
            <rect width="48" height="48" rx="12" fill="url(#logoGrad)"/>

            {{-- Motif batik sudut --}}
            <path d="M4 4 L10 4 L4 10 Z" fill="white" fill-opacity="0.15"/>
            <path d="M44 4 L38 4 L44 10 Z" fill="white" fill-opacity="0.15"/>
            <path d="M4 44 L10 44 L4 38 Z" fill="white" fill-opacity="0.15"/>
            <path d="M44 44 L38 44 L44 38 Z" fill="white" fill-opacity="0.15"/>

            {{-- Piring --}}
            <circle cx="24" cy="24" r="13" fill="white" fill-opacity="0.95"/>
            <circle cx="24" cy="24" r="10" fill="none" stroke="#f97316" stroke-width="1.2" stroke-opacity="0.4"/>
            <circle cx="24" cy="24" r="7"  fill="none" stroke="#f97316" stroke-width="0.8" stroke-opacity="0.25"/>

            {{-- Sendok --}}
            <path d="M18 13 C18 13 16.5 15 16.5 17.5 C16.5 19.5 17.5 20.5 18 21 L18 35 C18 35.6 18.4 36 19 36 C19.6 36 20 35.6 20 35 L20 21 C20.5 20.5 21.5 19.5 21.5 17.5 C21.5 15 20 13 20 13 C19.5 12.5 18.5 12.5 18 13Z"
                fill="white" fill-opacity="0.9"/>

            {{-- Garpu --}}
            <path d="M28 13 L28 18 M30 13 L30 18 M32 13 L32 18 M28 18 C28 18 27 19.5 28 21 L29 21 L29 35 C29 35.6 29.4 36 30 36 C30.6 36 31 35.6 31 35 L31 21 L32 21 C33 19.5 32 18 32 18"
                stroke="white" stroke-opacity="0.9" stroke-width="1.3" stroke-linecap="round" fill="none"/>

            {{-- Titik tengah motif --}}
            <circle cx="24" cy="24" r="1.5" fill="#f97316" fill-opacity="0.6"/>

            <defs>
                <linearGradient id="logoGrad" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#ea580c"/>
                    <stop offset="50%" stop-color="#f97316"/>
                    <stop offset="100%" stop-color="#c2410c"/>
                </linearGradient>
            </defs>
        </svg>
    </div>

    {{-- Text --}}
    <div class="leading-none">
        <div class="{{ $s['text1'] }} font-display font-bold leading-tight {{ $textColor ?? 'text-gray-900 dark:text-white' }}">
            Restoran
        </div>
        <div class="{{ $s['text2'] }} font-bold tracking-[0.15em] uppercase leading-tight"
            style="color: #f97316; font-family: 'Playfair Display', serif; letter-spacing: 0.12em;">
            NUSANTARA
        </div>
    </div>
</div>
