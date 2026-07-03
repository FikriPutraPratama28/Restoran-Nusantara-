@extends('admin.layouts.app')
@section('title', 'Reservasi')
@section('page-title', 'Manajemen Reservasi')
@section('page-subtitle', 'Kelola booking meja restoran')

@section('content')
@include('admin.partials.flash')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Calendar / List --}}
    <div class="lg:col-span-2 space-y-4">
        {{-- Stats (Bento Cards) --}}
        <div class="grid grid-cols-3 gap-4">
            @foreach([
                [
                    'label' => 'Hari Ini',
                    'val' => $stats['today'] ?? 0,
                    'svg' => '<svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                    'color' => 'bg-violet-500/10 text-violet-750'
                ],
                [
                    'label' => 'Besok',
                    'val' => $stats['tomorrow'] ?? 0,
                    'svg' => '<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'color' => 'bg-blue-500/10 text-blue-750'
                ],
                [
                    'label' => 'Minggu Ini',
                    'val' => $stats['week'] ?? 0,
                    'svg' => '<svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                    'color' => 'bg-emerald-500/10 text-emerald-750'
                ],
            ] as $s)
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 flex items-center justify-between gap-4">
                <div class="w-10 h-10 rounded-2xl {{ $s['color'] }} flex items-center justify-center">
                    {!! $s['svg'] !!}
                </div>
                <div class="text-right">
                    <div class="text-xl font-black text-gray-900 dark:text-white leading-none">{{ $s['val'] }}</div>
                    <div class="text-[10px] text-gray-400 dark:text-slate-400 font-bold uppercase tracking-wider mt-1.5">{{ $s['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Reservations List --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                <h3 class="font-bold text-gray-900 dark:text-white">Daftar Reservasi</h3>
                @php
                    $reservationsCount = method_exists($reservations, 'total') ? $reservations->total() : $reservations->count();
                @endphp
                <span class="text-xs bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 px-3 py-1 rounded-full font-bold">
                    {{ $reservationsCount }} reservasi
                </span>
            </div>
            @php
                $statusMap = [
                    'pending' => ['label' => 'Menunggu', 'class' => 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400'],
                    'confirmed' => ['label' => 'Konfirmasi', 'class' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400'],
                    'completed' => ['label' => 'Selesai', 'class' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'],
                    'cancelled' => ['label' => 'Batal', 'class' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400'],
                ];
                $areaMap = [
                    'indoor' => 'Indoor',
                    'outdoor' => 'Outdoor',
                ];
            @endphp
            <div class="divide-y divide-gray-100 dark:divide-slate-700/50">
                @forelse($reservations as $r)
                @php
                    $status = $statusMap[$r->status] ?? ['label' => ucfirst($r->status), 'class' => 'bg-gray-100 text-gray-600'];
                    $areaLabel = $areaMap[$r->table_area] ?? ucfirst($r->table_area ?? '-');
                    $code = $r->reservation_code ?? ('#RES-' . str_pad((string) $r->id, 4, '0', STR_PAD_LEFT));
                @endphp
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-all">
                    <div class="w-14 h-14 bg-violet-500/10 dark:bg-violet-900/20 rounded-2xl flex flex-col items-center justify-center flex-shrink-0">
                        <span class="text-violet-700 dark:text-violet-400 font-extrabold text-sm leading-tight">
                            {{ optional($r->reservation_time)->format('H:i') ?? substr($r->reservation_time, 0, 5) }}
                        </span>
                        <span class="text-violet-450 dark:text-slate-500 text-[10px] uppercase font-bold leading-none mt-0.5">
                            {{ optional($r->reservation_date)->format('d M') ?? $r->reservation_date }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-bold text-gray-800 dark:text-slate-200 text-sm">{{ $r->customer_name }}</p>
                            <span class="font-mono text-xs text-gray-400 dark:text-slate-500">{{ $code }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $r->number_of_guests }} tamu
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $areaLabel }} @if($r->table_number) ({{ $r->table_number }}) @endif
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $r->customer_phone }}
                            </span>
                            @if($r->notes)
                            <span class="flex items-center gap-1 text-violet-500 font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                {{ $r->notes }}
                            </span>
                            @endif
                        </div>
                        @if(!empty($r->ordered_items))
                        <div class="mt-2 flex flex-wrap gap-1">
                            @foreach($r->ordered_items as $item)
                                <span class="inline-flex items-center bg-gray-100 dark:bg-slate-700/50 text-gray-700 dark:text-slate-300 px-2.5 py-0.5 rounded-xl text-[10px] font-bold border border-gray-200 dark:border-slate-600/50">
                                    <svg class="w-2.5 h-2.5 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    {{ $item['name'] }} (x{{ $item['qty'] }})
                                </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider {{ $status['class'] }}">
                            {{ $status['label'] }}
                        </span>

                        @php $isPaid = ($r->payment_status ?? 'unpaid') === 'paid'; @endphp
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider <?php echo $isPaid ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400'; ?>">
                            <?php echo $isPaid ? 'Lunas' : 'Belum Lunas'; ?>
                        </span>
                        <form action="<?php echo route('admin.reservations.payment', $r->id); ?>" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="payment_status" value="<?php echo $isPaid ? 'unpaid' : 'paid'; ?>">
                            <button type="submit" title="<?php echo $isPaid ? 'Tandai Belum Lunas' : 'Tandai Lunas'; ?>" class="w-8 h-8 rounded-xl bg-violet-500/10 dark:bg-violet-500/20 text-violet-600 dark:text-violet-400 flex items-center justify-center hover:bg-violet-500/20 hover:scale-105 active:scale-95 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m3 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H10a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </button>
                        </form>

                        @if($r->status === 'pending')
                            <form action="{{ route('admin.reservations.status', $r->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" title="Konfirmasi Reservasi" class="w-8 h-8 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center hover:bg-emerald-500/20 hover:scale-105 active:scale-95 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.reservations.status', $r->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" title="Batalkan Reservasi" class="w-8 h-8 rounded-xl bg-red-500/10 dark:bg-red-500/20 text-red-500 dark:text-red-400 flex items-center justify-center hover:bg-red-500/20 hover:scale-105 active:scale-95 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        @elseif($r->status === 'confirmed')
                            <form action="{{ route('admin.reservations.status', $r->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" title="Selesaikan Reservasi" class="w-8 h-8 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 text-blue-650 dark:text-blue-400 flex items-center justify-center hover:bg-blue-500/20 hover:scale-105 active:scale-95 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.reservations.status', $r->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" title="Batalkan Reservasi" class="w-8 h-8 rounded-xl bg-red-500/10 dark:bg-red-500/20 text-red-500 dark:text-red-400 flex items-center justify-center hover:bg-red-500/20 hover:scale-105 active:scale-95 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-sm text-gray-500 dark:text-slate-400">
                    Belum ada data reservasi masuk.
                </div>
                @endforelse
            </div>
            @if(method_exists($reservations, 'links'))
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
                {{ $reservations->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Table Map / Denah Meja --}}
    <div class="space-y-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-1">Denah Meja</h3>
            <p class="text-xs text-slate-400 mb-5">Visualisasi layout meja saat ini</p>
            <div class="grid grid-cols-3 gap-2.5 mb-5">
                @php
                $tables = [
                    ['no'=>1,'status'=>'occupied'],['no'=>2,'status'=>'available'],['no'=>3,'status'=>'reserved'],
                    ['no'=>4,'status'=>'available'],['no'=>5,'status'=>'occupied'],['no'=>6,'status'=>'occupied'],
                    ['no'=>7,'status'=>'reserved'],['no'=>8,'status'=>'available'],['no'=>9,'status'=>'available'],
                    ['no'=>10,'status'=>'occupied'],['no'=>11,'status'=>'available'],['no'=>12,'status'=>'reserved'],
                ];
                @endphp
                @foreach($tables as $t)
                <div class="aspect-square rounded-2xl flex flex-col items-center justify-center text-xs font-black cursor-pointer hover:scale-105 transition-transform border border-black/5
                    {{ $t['status']==='occupied' ? 'bg-red-500/10 dark:bg-red-900/20 text-red-600 dark:text-red-400' : ($t['status']==='reserved' ? 'bg-amber-500/10 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400' : 'bg-emerald-500/10 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400') }}">
                    <span class="text-[9px] uppercase font-bold tracking-widest opacity-60">MEJA</span>
                    <span class="text-base font-extrabold mt-0.5">{{ $t['no'] }}</span>
                </div>
                @endforeach
            </div>

            <div class="space-y-2.5 text-xs">
                @foreach([
                    ['color'=>'bg-emerald-500','label'=>'Tersedia','count'=>5],
                    ['color'=>'bg-amber-500','label'=>'Dipesan','count'=>3],
                    ['color'=>'bg-red-500','label'=>'Terisi','count'=>4]
                ] as $l)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full {{ $l['color'] }}"></div>
                        <span class="text-gray-600 dark:text-slate-450">{{ $l['label'] }}</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $l['count'] }} meja</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Add (Bento form styling) --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Tambah Reservasi Tamu</h3>
            <div class="space-y-3">
                <input type="text" placeholder="Nama tamu" class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-950 dark:text-white text-xs font-semibold focus:ring-2 focus:ring-violet-500 outline-none">
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" class="px-3 py-2.5 rounded-2xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-950 dark:text-white text-xs font-semibold focus:ring-2 focus:ring-violet-500 outline-none">
                    <input type="time" class="px-3 py-2.5 rounded-2xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-950 dark:text-white text-xs font-semibold focus:ring-2 focus:ring-violet-500 outline-none">
                </div>
                <button class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-2xl transition-all shadow-md shadow-violet-600/10">Buat Reservasi</button>
            </div>
        </div>
    </div>
</div>
@endsection
