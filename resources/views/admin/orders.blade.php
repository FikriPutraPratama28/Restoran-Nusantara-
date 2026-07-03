@extends('admin.layouts.app')
@section('title','Pesanan')
@section('page-title','Manajemen Pesanan')
@section('page-subtitle','Kelola semua pesanan masuk secara real-time')

@section('content')
<div x-data="{
    filter: 'all',
    search: '',
    orders: [
        {id:'#1234',name:'Budi Santoso',  phone:'0812-3456',items:'Nasi Goreng x2, Es Teh x2',total:78000, type:'Dine In',  status:'selesai',  time:'12:30',date:'Hari ini'},
        {id:'#1235',name:'Siti Rahayu',   phone:'0813-2345',items:'Ayam Bakar x1, Jus Alpukat',total:63000, type:'Take Away',status:'dimasak',  time:'12:45',date:'Hari ini'},
        {id:'#1236',name:'Ahmad Fauzi',   phone:'0814-3456',items:'Paket Hemat A x1',          total:55000, type:'Dine In',  status:'diproses', time:'13:00',date:'Hari ini'},
        {id:'#1237',name:'Rina Marlina',  phone:'0815-4567',items:'Kopi Susu x3, Brownies x1', total:91000, type:'Delivery', status:'dikirim',  time:'13:15',date:'Hari ini'},
        {id:'#1238',name:'Doni Kusuma',   phone:'0816-5678',items:'Paket Keluarga x1',         total:150000,type:'Dine In',  status:'menunggu', time:'13:30',date:'Hari ini'},
        {id:'#1239',name:'Maya Sari',     phone:'0817-6789',items:'Sate Ayam x2, Es Teh x2',   total:76000, type:'Take Away',status:'selesai',  time:'11:00',date:'Hari ini'},
        {id:'#1240',name:'Rudi Hartono',  phone:'0818-7890',items:'Mie Goreng Seafood x1',     total:40000, type:'Delivery', status:'selesai',  time:'10:30',date:'Hari ini'},
        {id:'#1241',name:'Lina Wati',     phone:'0819-8901',items:'Smoothie Bowl x2',           total:76000, type:'Dine In',  status:'dimasak',  time:'13:45',date:'Hari ini'},
    ],
    get filtered() {
        return this.orders.filter(o => {
            const matchFilter = this.filter === 'all' || o.status === this.filter;
            const matchSearch = !this.search || o.name.toLowerCase().includes(this.search.toLowerCase()) || o.id.includes(this.search);
            return matchFilter && matchSearch;
        });
    },
    statusColor(s) {
        return {selesai:'bg-emerald-100 text-emerald-700',dimasak:'bg-orange-100 text-orange-700',diproses:'bg-blue-100 text-blue-700',dikirim:'bg-cyan-100 text-cyan-700',menunggu:'bg-yellow-100 text-yellow-700'}[s] || 'bg-gray-100 text-gray-600';
    },
    statusLabel(s) {
        return {selesai:'Selesai',dimasak:'Dimasak',diproses:'Diproses',dikirim:'Dikirim',menunggu:'Menunggu'}[s] || s;
    },
    formatPrice(p) { return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(p); }
}">

    {{-- Stats Strip --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        @foreach([['label'=>'Semua','val'=>'all','count'=>8,'color'=>'violet'],['label'=>'Menunggu','val'=>'menunggu','count'=>1,'color'=>'yellow'],['label'=>'Diproses','val'=>'diproses','count'=>1,'color'=>'blue'],['label'=>'Dimasak','val'=>'dimasak','count'=>2,'color'=>'orange'],['label'=>'Selesai','val'=>'selesai','count'=>3,'color'=>'emerald']] as $f)
        <button @click="filter='{{ $f['val'] }}'"
            :class="filter==='{{ $f['val'] }}' ? 'ring-2 ring-violet-500 bg-violet-50 dark:bg-violet-900/20' : 'bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700'"
            class="rounded-2xl p-4 text-left shadow-sm border border-gray-100 dark:border-slate-700 transition-all">
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $f['count'] }}</div>
            <div class="text-sm text-gray-500 dark:text-slate-400">{{ $f['label'] }}</div>
        </button>
        @endforeach
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        {{-- Toolbar --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                <input x-model="search" type="text" placeholder="Cari pesanan atau nama..." class="pl-9 pr-4 py-2 text-sm bg-gray-100 dark:bg-slate-700 rounded-xl border-0 text-gray-700 dark:text-slate-300 placeholder-gray-400 focus:ring-2 focus:ring-violet-500 outline-none w-64">
            </div>
            <div class="flex gap-2">
                <button class="flex items-center gap-2 text-sm px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filter
                </button>
                <button class="flex items-center gap-2 text-sm px-4 py-2 bg-violet-600 text-white rounded-xl hover:bg-violet-700 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Pesanan Baru
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-700/50 text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-6 py-3">Order ID</th>
                        <th class="text-left px-4 py-3">Pelanggan</th>
                        <th class="text-left px-4 py-3">Item</th>
                        <th class="text-left px-4 py-3">Tipe</th>
                        <th class="text-left px-4 py-3">Total</th>
                        <th class="text-left px-4 py-3">Waktu</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    <template x-for="o in filtered" :key="o.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-violet-600 text-sm" x-text="o.id"></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-violet-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" x-text="o.name.charAt(0)"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-slate-200" x-text="o.name"></p>
                                        <p class="text-xs text-gray-400" x-text="o.phone"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 max-w-[160px] truncate" x-text="o.items"></td>
                            <td class="px-4 py-4">
                                <span class="text-xs px-2 py-1 rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 font-medium" x-text="o.type"></span>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white" x-text="formatPrice(o.total)"></td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400" x-text="o.time"></td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" :class="statusColor(o.status)" x-text="statusLabel(o.status)"></span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex gap-1">
                                    <button class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-violet-100 hover:text-violet-600 transition-all" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 hover:bg-emerald-100 hover:text-emerald-600 transition-all" title="Update Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-slate-700">
            <p class="text-sm text-gray-500 dark:text-slate-400">Menampilkan <span class="font-bold text-gray-900 dark:text-white" x-text="filtered.length"></span> pesanan</p>
            <div class="flex gap-1">
                @foreach([1,2,3] as $item) <button class="w-8 h-8 rounded-lg text-sm {{ $loop->first ? 'bg-violet-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 hover:bg-gray-200' }} transition-all">{{ $item }}</button> @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
