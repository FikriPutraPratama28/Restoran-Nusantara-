@extends('karyawan.layouts.app')
@section('title', 'Pengajuan Cuti')
@section('page-title', 'Pengajuan Cuti')
@section('page-subtitle', 'Ajukan dan pantau status cuti Anda')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-4 flex items-center gap-3">
        <span class="text-emerald-500 text-xl">✅</span>
        <p class="text-emerald-700 dark:text-emerald-400 text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 flex items-center gap-3">
        <span class="text-red-500 text-xl">⚠️</span>
        <p class="text-red-700 dark:text-red-400 text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

{{-- Kuota Cuti --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 text-center">
            <div class="text-3xl font-bold text-violet-600 dark:text-violet-400">{{ $leaveQuota }}</div>
            <div class="text-sm text-gray-500 dark:text-slate-400 mt-1">Total Kuota/Tahun</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 text-center">
            <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $usedLeave }}</div>
            <div class="text-sm text-gray-500 dark:text-slate-400 mt-1">Sudah Digunakan</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 text-center">
            <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $remainingLeave }}</div>
            <div class="text-sm text-gray-500 dark:text-slate-400 mt-1">Sisa Cuti</div>
        </div>
    </div>

    {{-- Form Pengajuan --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700" x-data="{ open: false }">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">🏖️ Ajukan Cuti Baru</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">Isi form di bawah untuk mengajukan cuti</p>
            </div>
            <button @click="open = !open"
                :class="open ? 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300' : 'bg-blue-600 hover:bg-blue-700 text-white'"
                class="px-4 py-2 text-sm font-semibold rounded-xl transition-all flex items-center gap-2">
                <svg :class="open ? 'rotate-45' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span x-text="open ? 'Tutup' : 'Ajukan Cuti'"></span>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="mt-5 pt-5 border-t border-gray-100 dark:border-slate-700">
            <form method="POST" action="{{ route('karyawan.leave.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Jenis Cuti <span class="text-red-500">*</span></label>
                        <select name="type" required
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('type') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }} bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Pilih jenis cuti...</option>
                            <option value="cuti_tahunan" {{ old('type') === 'cuti_tahunan' ? 'selected' : '' }}>🏖️ Cuti Tahunan</option>
                            <option value="sakit"        {{ old('type') === 'sakit'        ? 'selected' : '' }}>🤒 Sakit</option>
                            <option value="izin"         {{ old('type') === 'izin'         ? 'selected' : '' }}>📋 Izin</option>
                            <option value="cuti_khusus"  {{ old('type') === 'cuti_khusus'  ? 'selected' : '' }}>⭐ Cuti Khusus</option>
                        </select>
                        @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div x-data="{ start: '', end: '', days: 0 }">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" x-model="start" :min="new Date().toISOString().split('T')[0]"
                            value="{{ old('start_date') }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        @error('start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Alasan <span class="text-red-500">*</span></label>
                        <textarea name="reason" rows="3" required minlength="10" maxlength="500"
                            placeholder="Jelaskan alasan pengajuan cuti Anda (min. 10 karakter)..."
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('reason') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }} bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('reason') }}</textarea>
                        @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-blue-600/30 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Riwayat Pengajuan --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Pengajuan</h3>
        </div>
        @if($leaves->isEmpty())
        <div class="py-16 text-center">
            <div class="text-5xl mb-4">🏖️</div>
            <p class="text-gray-500 dark:text-slate-400 text-sm">Belum ada pengajuan cuti</p>
        </div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($leaves as $leave)
            @php $sb = $leave->status_badge; @endphp
            <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl mt-0.5">{{ str_contains($leave->type, 'sakit') ? '🤒' : (str_contains($leave->type, 'izin') ? '📋' : (str_contains($leave->type, 'khusus') ? '⭐' : '🏖️')) }}</div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-slate-200 text-sm">{{ $leave->type_label }}</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">
                                {{ $leave->start_date->format('d M Y') }} – {{ $leave->end_date->format('d M Y') }}
                                <span class="font-semibold text-gray-700 dark:text-slate-300">· {{ $leave->total_days }} hari</span>
                            </p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1 italic">"{{ $leave->reason }}"</p>
                            @if($leave->admin_notes)
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">💬 Admin: {{ $leave->admin_notes }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sb['class'] }}">{{ $sb['label'] }}</span>
                        @if($leave->status === 'menunggu')
                        <form method="POST" action="{{ route('karyawan.leave.destroy', $leave) }}"
                            onsubmit="return confirm('Batalkan pengajuan cuti ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Batalkan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
            {{ $leaves->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
