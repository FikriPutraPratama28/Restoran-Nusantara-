@extends('admin.layouts.app')
@section('title', 'Pengajuan Cuti')
@section('page-title', 'Pengajuan Cuti')
@section('page-subtitle', 'Kelola permohonan cuti karyawan')

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 mb-4 flex items-center gap-3">
    <span class="text-emerald-500 text-xl">✅</span>
    <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
</div>
@endif

{{-- Filter --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 mb-4">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="">Semua Status</option>
            <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>⏳ Menunggu</option>
            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
            <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>❌ Ditolak</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">Filter</button>
    </form>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    @if($leaves->isEmpty())
    <div class="py-16 text-center">
        <div class="text-5xl mb-4">🏖️</div>
        <p class="text-gray-500 dark:text-slate-400 text-sm">Belum ada pengajuan cuti</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3.5 uppercase tracking-wider">Karyawan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Jenis</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Periode</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Hari</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Alasan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 dark:text-slate-400 px-4 py-3.5 uppercase tracking-wider">Status</th>
                    <th class="text-right text-xs font-semibold text-gray-500 dark:text-slate-400 px-6 py-3.5 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($leaves as $leave)
                @php $sb = $leave->status_badge; @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $leave->employee->user->avatar_url }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm">{{ $leave->employee->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $leave->employee->jabatan }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-slate-300">{{ $leave->type_label }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400">
                        {{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}
                    </td>
                    <td class="px-4 py-4 text-sm font-bold text-gray-800 dark:text-slate-200">{{ $leave->total_days }}h</td>
                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-slate-400 max-w-[200px]">
                        <p class="truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</p>
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sb['class'] }}">{{ $sb['label'] }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($leave->status === 'menunggu')
                        <div class="flex items-center justify-end gap-2" x-data="{ open: false }">
                            <button @click="open = true"
                                class="px-3 py-1.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold rounded-lg transition-all">
                                Proses
                            </button>
                            {{-- Modal --}}
                            <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="open = false">
                                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl">
                                    <h4 class="font-bold text-gray-900 dark:text-white mb-1">Proses Pengajuan Cuti</h4>
                                    <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ $leave->employee->user->name }} · {{ $leave->type_label }} · {{ $leave->total_days }} hari</p>
                                    <form method="POST" action="{{ route('admin.leaves.approve', $leave) }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Keputusan</label>
                                            <div class="flex gap-3">
                                                <label class="flex-1 flex items-center gap-2 p-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-900/20 transition-all">
                                                    <input type="radio" name="action" value="disetujui" class="text-emerald-600" required>
                                                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-300">✅ Setujui</span>
                                                </label>
                                                <label class="flex-1 flex items-center gap-2 p-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/20 transition-all">
                                                    <input type="radio" name="action" value="ditolak" class="text-red-600">
                                                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-300">❌ Tolak</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Catatan Admin (opsional)</label>
                                            <textarea name="admin_notes" rows="2" placeholder="Alasan persetujuan/penolakan..."
                                                class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none resize-none"></textarea>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" @click="open = false"
                                                class="flex-1 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all">Batal</button>
                                            <button type="submit"
                                                class="flex-1 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-right text-xs text-gray-400 dark:text-slate-500">
                            @if($leave->approvedBy)
                            Oleh: {{ $leave->approvedBy->name }}<br>
                            {{ $leave->approved_at?->format('d M Y') }}
                            @endif
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
