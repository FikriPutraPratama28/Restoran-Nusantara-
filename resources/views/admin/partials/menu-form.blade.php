@php $p = $prefix; @endphp

{{-- Upload Gambar --}}
<div x-data="{
    previewUrl: '',
    isDragging: false,
    removeImg: false,
    handleFile(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => { this.previewUrl = e.target.result; this.removeImg = false; };
        reader.readAsDataURL(file);
    },
    handleDrop(e) { this.isDragging = false; this.handleFile(e.dataTransfer.files[0]); },
    remove() { this.previewUrl = ''; this.removeImg = true; this.$refs.fi.value = ''; }
}" x-init="previewUrl = document.getElementById('{{ $p }}_preview')?.src || ''">

    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Foto Menu</label>

    {{-- Preview --}}
    <div x-show="previewUrl" class="relative mb-3 rounded-xl overflow-hidden group h-40">
        <img :src="previewUrl" id="{{ $p }}_preview" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
            <button type="button" @click="$refs.fi.click()" class="bg-white text-gray-900 text-xs font-semibold px-4 py-2 rounded-xl hover:bg-gray-100 transition-all shadow-lg flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
                <span>Ganti</span>
            </button>
            <button type="button" @click="remove()" class="bg-red-500 text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-red-650 transition-all shadow-lg flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Hapus</span>
            </button>
        </div>
    </div>

    {{-- Drop Zone --}}
    <div x-show="!previewUrl"
        @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="handleDrop($event)"
        @click="$refs.fi.click()"
        :class="isDragging ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/20' : 'border-gray-300 dark:border-slate-600 hover:border-violet-400 hover:bg-gray-50 dark:hover:bg-slate-700/50'"
        class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-all duration-200">
        <div class="flex flex-col items-center gap-2">
            <div class="w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Klik atau drag & drop gambar</p>
            <p class="text-xs text-gray-400">PNG, JPG, WEBP — Maks. 5MB</p>
            <button type="button" class="mt-1 bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 mx-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>Pilih Gambar</span>
            </button>
        </div>
    </div>

    <input x-ref="fi" type="file" name="image" accept="image/*" class="hidden" @change="handleFile($event.target.files[0])">
    <input type="hidden" name="remove_image" :value="removeImg ? '1' : '0'">
</div>

{{-- Nama --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Nama Menu <span class="text-red-500">*</span></label>
    <input id="{{ $p }}_name" name="name" type="text" placeholder="Contoh: Nasi Goreng Spesial" required
        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none placeholder-gray-400">
</div>

{{-- Deskripsi --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
    <textarea id="{{ $p }}_description" name="description" rows="2" placeholder="Deskripsi singkat menu..."
        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none resize-none placeholder-gray-400"></textarea>
</div>

{{-- Kategori & Harga --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Kategori <span class="text-red-500">*</span></label>
        <select id="{{ $p }}_category" name="category" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="makanan">Makanan</option>
            <option value="minuman">Minuman</option>
            <option value="dessert">Dessert</option>
            <option value="snack">Snack</option>
            <option value="paket">Paket Mabar</option>
            <option value="seafood">Seafood</option>
            <option value="aneka-snack">Aneka Snack</option>
            <option value="aneka-sayur">Aneka Sayur</option>
            <option value="nasi-kotak">Nasi Kotak</option>
            <option value="acara-khusus">Acara Khusus</option>
            <option value="iga">Iga</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
            <input id="{{ $p }}_price" name="price" type="text" inputmode="numeric" placeholder="0" required
                x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        </div>
    </div>
</div>

{{-- Harga Coret & Label --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Harga Coret</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
            <input id="{{ $p }}_original_price" name="original_price" type="text" inputmode="numeric" placeholder="0"
                x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        </div>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Label</label>
        <select id="{{ $p }}_label" name="label"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="">Tidak ada</option>
            <option value="best-seller">Best Seller</option>
            <option value="new">Baru</option>
            <option value="popular">Populer</option>
        </select>
    </div>
</div>

{{-- Toggle switches --}}
<div class="space-y-2">
    @foreach([
        ['id'=>'is_stock', 'label'=>'Status Stok', 'desc'=>'Menu tersedia untuk dipesan', 'checked'=>true],
        ['id'=>'is_promo',  'label'=>'Badge Promo',  'desc'=>'Tampilkan label PROMO',        'checked'=>false],
        ['id'=>'is_new',    'label'=>'Menu Baru',    'desc'=>'Tampilkan label Baru',          'checked'=>false],
    ] as $toggle)
    <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-slate-700/50 rounded-xl" x-data="{on: {{ $toggle['checked'] ? 'true' : 'false' }}}">
        <div>
            <p class="text-sm font-semibold text-gray-700 dark:text-slate-300">{{ $toggle['label'] }}</p>
            <p class="text-xs text-gray-400">{{ $toggle['desc'] }}</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="{{ $toggle['id'] }}" :value="on ? '1' : '0'">
            <button type="button" @click="on=!on"
                :class="on ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-slate-600'"
                class="relative w-11 h-6 rounded-full transition-all duration-200">
                <span :class="on ? 'translate-x-5' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></span>
            </button>
        </div>
    </div>
    @endforeach
</div>
