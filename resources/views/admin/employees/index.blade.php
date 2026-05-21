@extends('admin.layouts.app')
@section('title', 'Manajemen Karyawan')
@section('page-title', 'Manajemen Karyawan')
@section('page-subtitle', 'Kelola data karyawan restoran')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $statCards = [
        ['label'=>'Total Karyawan', 'value'=>$stats['total'],    'icon'=>'👥', 'color'=>'from-violet-500 to-purple-600'],
        ['label'=>'Aktif',          'value'=>$stats['aktif'],    'icon'=>'✅', 'color'=>'from-emerald-500 to-teal-600'],
        ['label'=>'Sedang Cuti',    'value'=>$stats['cuti'],     'icon'=>'🏖️', 'color'=>'from-yellow-500 to-amber-600'],
        ['label'=>'Nonaktif',       'value'=>$stats['nonaktif'], 'icon'=>'⛔', 'color'=>'from-red-500 to-rose-600'],
    ];
    @endphp
    @foreach($statCards as $s)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-gradient-to-br {{ $s['color'] }} rounded-xl flex items-center justify-center text-lg shadow">{{ $s['icon'] }}</div>
        </div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $s['value'] }}</div>
        <div class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Toolbar --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 mb-4">
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..."
                class="flex-1 min-w-[180px] px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            <select name="jabatan" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                <option value="">Semua Jabatan</option>
                @foreach(['Chef','Sous Chef','Kasir','Pelayan','Barista','Cleaning Service','Security','Manager'] as $j)
                <option value="{{ $j }}" {{ request('jabatan') === $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
            <select name="shift" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                <option value="">Semua Shift</option>
                <option value="pagi"  {{ request('shift') === 'pagi'  ? 'selected' : '' }}>Pagi</option>
                <option value="siang" {{ request('shift') === 'siang' ? 'selected' : '' }}>Siang</option>
                <option value="malam" {{ request('shift') === 'malam' ? 'selected' : '' }}>Malam</option>
                <option value="full"  {{ request('shift') === 'full'  ? 'selected' : '' }}>Full Day</option>
            </select>
            <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="cuti"     {{ request('status') === 'cuti'     ? 'selected' : '' }}>Cuti</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">Cari</button>
            @if(request()->hasAny(['search','jabatan','shift','status']))
            <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all">Reset</a>
            @endif
        </form>
        @if(auth()->user()->hasPermission('edit_employee'))
        <a href="{{ route('admin.employees.create') }}"
            class="flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-violet-600/30 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Karyawan
        </a>
        @endif
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 mb-4 flex items-center gap-3">
    <span class="text-emerald-500 text-xl">✅</span>
    <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
</div>
@endif

{{-- Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    @if($employees->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="text-5xl mb-4">👥</div>
        <h3 class="text-lg font-bold text-gray-700 dark:text-slate-300 mb-2">Belum ada karyawan</h3>
        <p class="text-gray-400 dark:text-slate-500 text-sm mb-5">Tambahkan karyawan pertama Anda</p>
        <a href="{{ route('admin.employees.create') }}" class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">+ Tambah Karyawan</a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3.5 uppercase tracking-wider">Karyawan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Kode</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Jabatan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Shift</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Bergabung</th>
                    <th class="text-right text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3.5 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($employees as $emp)
                @php $badge = $emp->status_badge; @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $emp->user->avatar_url }}" alt="{{ $emp->user->name }}"
                                class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border-2 border-gray-100 dark:border-slate-600">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm">{{ $emp->user->name }}</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500">{{ $emp->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="font-mono text-xs font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20 px-2 py-1 rounded-lg">{{ $emp->employee_code }}</span>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $emp->jabatan }}</td>
                    <td class="px-4 py-4">
                        <span class="text-xs text-gray-600 dark:text-slate-400 capitalize">{{ ucfirst($emp->shift) }}</span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400">
                        {{ $emp->join_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.employees.show', $emp) }}"
                                class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if(auth()->user()->hasPermission('edit_employee'))
                            <a href="{{ route('admin.employees.edit', $emp) }}"
                                class="p-2 text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-lg transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('delete_data'))
                            <form method="POST" action="{{ route('admin.employees.destroy', $emp) }}"
                                onsubmit="return confirm('Hapus karyawan {{ $emp->user->name }}? Semua data absensi juga akan terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
