@extends('admin.layouts.app')
@section('title','Reservasi')
@section('page-title','Manajemen Reservasi')
@section('page-subtitle','Kelola booking meja restoran')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Calendar / List --}}
    <div class="lg:col-span-2 space-y-4">
        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4">
            @foreach([['label'=>'Hari Ini','val'=>'12','icon'=>'📅','color'=>'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400'],['label'=>'Besok','val'=>'8','icon'=>'📆','color'=>'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'],['label'=>'Minggu Ini','val'=>'47','icon'=>'🗓️','color'=>'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400']] as $s)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 text-center">
                <div class="text-2xl mb-1">{{ $s['icon'] }}</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $s['val'] }}</div>
                <div class="text-xs text-gray-500 dark:text-slate-400">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Reservations List --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                <h3 class="font-bold text-gray-900 dark:text-white">Reservasi Hari Ini</h3>
                <span class="text-xs bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 px-3 py-1 rounded-full font-semibold">12 reservasi</span>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-slate-700">
                @php
                $reservations = [
                    ['code'=>'#RES-A1B2','name'=>'Budi Santoso',  'time'=>'12:00','guests'=>4,'area'=>'Indoor', 'phone'=>'0812-3456','status'=>'konfirmasi','notes'=>'Anniversary'],
                    ['code'=>'#RES-C3D4','name'=>'Siti Rahayu',   'time'=>'13:30','guests'=>2,'area'=>'Outdoor','phone'=>'0813-2345','status'=>'menunggu', 'notes'=>''],
                    ['code'=>'#RES-E5F6','name'=>'Ahmad Fauzi',   'time'=>'18:00','guests'=>6,'area'=>'VIP',    'phone'=>'0814-3456','status'=>'konfirmasi','notes'=>'Ulang tahun'],
                    ['code'=>'#RES-G7H8','name'=>'Rina Marlina',  'time'=>'19:30','guests'=>3,'area'=>'Indoor', 'phone'=>'0815-4567','status'=>'konfirmasi','notes'=>''],
                    ['code'=>'#RES-I9J0','name'=>'Doni Kusuma',   'time'=>'20:00','guests'=>8,'area'=>'Outdoor','phone'=>'0816-5678','status'=>'batal',    'notes'=>''],
                ];
                @endphp
                @foreach($reservations as $r)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-all">
                    <div class="w-14 h-14 bg-violet-100 dark:bg-violet-900/30 rounded-2xl flex flex-col items-center justify-center flex-shrink-0">
                        <span class="text-violet-700 dark:text-violet-400 font-bold text-sm">{{ $r['time'] }}</span>
                        <span class="text-violet-400 text-xs">WIB</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm">{{ $r['name'] }}</p>
                            <span class="font-mono text-xs text-gray-400">{{ $r['code'] }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-slate-400">
                            <span>👥 {{ $r['guests'] }} orang</span>
                            <span>🪑 {{ $r['area'] }}</span>
                            <span>📞 {{ $r['phone'] }}</span>
                            @if($r['notes'])<span class="text-violet-500">🎉 {{ $r['notes'] }}</span>@endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $r['status']==='konfirmasi' ? 'bg-emerald-100 text-emerald-700' : ($r['status']==='menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                            {{ ucfirst($r['status']) }}
                        </span>
                        <button class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-violet-100 hover:text-violet-600 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Table Map --}}
    <div class="space-y-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Denah Meja</h3>
            <div class="grid grid-cols-3 gap-2 mb-4">
                @php
                $tables = [
                    ['no'=>1,'status'=>'occupied'],['no'=>2,'status'=>'available'],['no'=>3,'status'=>'reserved'],
                    ['no'=>4,'status'=>'available'],['no'=>5,'status'=>'occupied'],['no'=>6,'status'=>'occupied'],
                    ['no'=>7,'status'=>'reserved'],['no'=>8,'status'=>'available'],['no'=>9,'status'=>'available'],
                    ['no'=>10,'status'=>'occupied'],['no'=>11,'status'=>'available'],['no'=>12,'status'=>'reserved'],
                ];
                @endphp
                @foreach($tables as $t)
                <div class="aspect-square rounded-xl flex flex-col items-center justify-center text-xs font-bold cursor-pointer hover:scale-105 transition-transform
                    {{ $t['status']==='occupied' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : ($t['status']==='reserved' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' : 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400') }}">
                    <span class="text-lg">🪑</span>
                    <span>{{ $t['no'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="space-y-2 text-xs">
                @foreach([['color'=>'bg-emerald-500','label'=>'Tersedia','count'=>5],['color'=>'bg-yellow-500','label'=>'Dipesan','count'=>3],['color'=>'bg-red-500','label'=>'Terisi','count'=>4]] as $l)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full {{ $l['color'] }}"></div><span class="text-gray-600 dark:text-slate-400">{{ $l['label'] }}</span></div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $l['count'] }} meja</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Add --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Tambah Reservasi</h3>
            <div class="space-y-3">
                <input type="text" placeholder="Nama tamu" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
                    <input type="time" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
                </div>
                <button class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-xl transition-all">Buat Reservasi</button>
            </div>
        </div>
    </div>
</div>
@endsection
