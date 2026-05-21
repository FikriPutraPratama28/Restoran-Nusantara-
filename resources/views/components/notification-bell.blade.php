<div x-data="{ open: false }" class="relative">
    {{-- Bell Button --}}
    <button @click="open = !open" @click.outside="open = false"
        class="relative w-9 h-9 rounded-xl flex items-center justify-center transition-all
        {{ $dark ?? false
            ? 'text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700'
            : 'text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($_unreadCount > 0)
        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 shadow-lg">
            {{ $_unreadCount > 99 ? '99+' : $_unreadCount }}
        </span>
        @else
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-gray-300 dark:bg-slate-600 rounded-full"></span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden z-50">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
            <div class="flex items-center gap-2">
                <span class="font-bold text-gray-900 dark:text-white text-sm">Notifikasi</span>
                @if($_unreadCount > 0)
                <span class="text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-2 py-0.5 rounded-full">
                    {{ $_unreadCount }} baru
                </span>
                @endif
            </div>
            @if($_unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs text-violet-600 dark:text-violet-400 hover:underline font-medium">
                    Tandai semua dibaca
                </button>
            </form>
            @endif
        </div>

        {{-- List --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-slate-700">
            @forelse($_latestNotifs as $notif)
            <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors {{ $notif->isUnread() ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                <div class="w-9 h-9 {{ $notif->color }} rounded-xl flex items-center justify-center text-base flex-shrink-0 mt-0.5">
                    {{ $notif->icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-semibold text-gray-800 dark:text-slate-200 leading-tight">
                            {{ $notif->title }}
                        </p>
                        @if($notif->isUnread())
                        <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                    <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
                @if($notif->url)
                <a href="{{ $notif->url }}"
                    onclick="fetch('{{ route('notifications.read', $notif) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}})"
                    class="flex-shrink-0 text-violet-500 hover:text-violet-700 mt-1" title="Buka">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
                @endif
            </div>
            @empty
            <div class="py-10 text-center">
                <div class="text-4xl mb-2">🔔</div>
                <p class="text-sm text-gray-400 dark:text-slate-500">Tidak ada notifikasi</p>
            </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-700 text-center bg-gray-50 dark:bg-slate-700/50">
            <a href="{{ route('notifications.index') }}" @click="open = false"
                class="text-violet-600 dark:text-violet-400 text-sm font-semibold hover:underline">
                Lihat semua notifikasi →
            </a>
        </div>
    </div>
</div>
