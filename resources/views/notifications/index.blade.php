@php
// Deteksi layout berdasarkan role user
$isAdmin    = session('admin_logged_in') || (auth()->check() && auth()->user()->isAdmin());
$isKaryawan = auth()->check() && auth()->user()->isKaryawan();
$layout     = $isAdmin ? 'admin.layouts.app' : 'karyawan.layouts.app';
@endphp

@extends($layout)
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Semua notifikasi Anda')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Semua Notifikasi</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">
                {{ $notifications->total() }} total &nbsp;·&nbsp;
                <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $_unreadCount }} belum dibaca</span>
            </p>
        </div>
        @if($_unreadCount > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit"
                class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-violet-600/30">
                ✓ Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 flex items-center gap-3">
        <span class="text-emerald-500 text-xl">✅</span>
        <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- List --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        @if($notifications->isEmpty())
        <div class="py-16 text-center">
            <div class="text-6xl mb-4">🔔</div>
            <h3 class="text-lg font-bold text-gray-700 dark:text-slate-300 mb-2">Tidak ada notifikasi</h3>
            <p class="text-gray-400 dark:text-slate-500 text-sm">Notifikasi akan muncul di sini saat ada aktivitas baru</p>
        </div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($notifications as $notif)
            <div class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors
                {{ $notif->isUnread() ? 'bg-blue-50/40 dark:bg-blue-900/10' : '' }}">

                {{-- Icon --}}
                <div class="w-11 h-11 {{ $notif->color }} rounded-xl flex items-center justify-center text-xl flex-shrink-0 mt-0.5">
                    {{ $notif->icon }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-800 dark:text-slate-200">
                                {{ $notif->title }}
                                @if($notif->isUnread())
                                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full ml-1 mb-0.5"></span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1 leading-relaxed">{{ $notif->message }}</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $notif->created_at->diffForHumans() }}
                                &nbsp;·&nbsp;
                                {{ $notif->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($notif->url)
                            <a href="{{ $notif->url }}"
                                onclick="fetch('{{ route('notifications.read', $notif) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})"
                                class="p-2 text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-lg transition-all" title="Buka">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            @endif
                            <form method="POST" action="{{ route('notifications.destroy', $notif) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
            {{ $notifications->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
