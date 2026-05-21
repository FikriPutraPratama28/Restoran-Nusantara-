@extends('admin.layouts.app')
@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')
@section('page-subtitle', 'Perbarui data karyawan')

@section('content')
<div class="max-w-3xl mx-auto">

    <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-gray-500 dark:text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 text-sm font-medium mb-6 transition-colors group">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
        Kembali ke Daftar Karyawan
    </a>

    <form method="POST" action="{{ route('admin.employees.update', $employee) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Informasi Akun --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                <span class="w-7 h-7 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center text-sm">👤</span>
                Informasi Akun
                <span class="ml-auto font-mono text-xs text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20 px-2 py-1 rounded-lg">{{ $employee->employee_code }}</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Foto Profil</label>
                    <div class="flex items-center gap-4" x-data="{ preview: '{{ $employee->user->avatar_url }}' }">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 border-2 border-gray-200 dark:border-slate-600">
                            <img :src="preview" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <input type="file" name="avatar" accept="image/*"
                                @change="preview = URL.createObjectURL($event.target.files[0])"
                                class="block text-sm text-gray-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 dark:file:bg-violet-900/30 dark:file:text-violet-400 hover:file:bg-violet-100 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $employee->user->name) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }} bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $employee->user->email) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200 dark:border-slate-600' }} bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">No. HP</label>
                    <input type="tel" name="phone" value="{{ old('phone', $employee->user->phone) }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Password Baru <span class="text-gray-400 font-normal text-xs">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Min. 8 karakter"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                </div>
            </div>
        </div>

        {{-- Informasi Pekerjaan --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                <span class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-sm">💼</span>
                Informasi Pekerjaan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $employee->jabatan) }}" required list="jabatan-list"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                    <datalist id="jabatan-list">
                        @foreach(['Chef','Sous Chef','Kasir','Pelayan','Barista','Cleaning Service','Security','Manager','Supervisor'] as $j)
                        <option value="{{ $j }}">
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Shift Kerja <span class="text-red-500">*</span></label>
                    <select name="shift" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                        <option value="pagi"  {{ old('shift', $employee->shift) === 'pagi'  ? 'selected' : '' }}>🌅 Pagi (06:00–14:00)</option>
                        <option value="siang" {{ old('shift', $employee->shift) === 'siang' ? 'selected' : '' }}>☀️ Siang (14:00–22:00)</option>
                        <option value="malam" {{ old('shift', $employee->shift) === 'malam' ? 'selected' : '' }}>🌙 Malam (22:00–06:00)</option>
                        <option value="full"  {{ old('shift', $employee->shift) === 'full'  ? 'selected' : '' }}>🕐 Full Day (08:00–17:00)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tanggal Bergabung <span class="text-red-500">*</span></label>
                    <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Status</label>
                    <select name="status" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                        <option value="aktif"    {{ old('status', $employee->status) === 'aktif'    ? 'selected' : '' }}>✅ Aktif</option>
                        <option value="cuti"     {{ old('status', $employee->status) === 'cuti'     ? 'selected' : '' }}>🏖️ Cuti</option>
                        <option value="nonaktif" {{ old('status', $employee->status) === 'nonaktif' ? 'selected' : '' }}>⛔ Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Gaji (Rp)</label>
                    <input type="number" name="salary" value="{{ old('salary', $employee->salary) }}" min="0"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Kontak Darurat</label>
                    <input type="tel" name="emergency_contact" value="{{ old('emergency_contact', $employee->emergency_contact) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Alamat</label>
                    <textarea name="address" rows="2"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all resize-none">{{ old('address', $employee->address) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Catatan</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none transition-all resize-none">{{ old('notes', $employee->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.employees.index') }}"
                class="px-6 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all">Batal</a>
            <button type="submit"
                class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-violet-600/30 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
