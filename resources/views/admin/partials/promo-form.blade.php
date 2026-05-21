@php $edit = $edit ?? false; @endphp

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Judul <span class="text-red-500">*</span></label>
        <input type="text" name="title" :value="editPromo?.title||''" required placeholder="Diskon 30% Makanan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Kode Voucher <span class="text-red-500">*</span></label>
        <input type="text" name="code" :value="editPromo?.code||''" required placeholder="SENIN30" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none font-mono uppercase">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
    <textarea name="description" rows="2" :value="editPromo?.description||''" placeholder="Berlaku setiap hari Senin..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none resize-none"></textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tipe Diskon</label>
        <select name="discount_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="percent" :selected="!editPromo||editPromo.discount_type==='percent'">% Persen</option>
            <option value="fixed" :selected="editPromo?.discount_type==='fixed'">Rp Nominal</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Nilai Diskon <span class="text-red-500">*</span></label>
        <input type="number" name="discount_value" :value="editPromo?.discount_value||''" required min="1" placeholder="30" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Min. Pembelian (Rp)</label>
        <input type="number" name="min_purchase" :value="editPromo?.min_purchase||0" min="0" placeholder="0" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Berlaku Hingga</label>
        <input type="date" name="valid_until" :value="editPromo?.valid_until||''" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Icon Emoji</label>
        <input type="text" name="icon" :value="editPromo?.icon||'🎁'" placeholder="🎁" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none text-center text-xl">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Badge</label>
        <input type="text" name="badge" :value="editPromo?.badge||''" placeholder="Mingguan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Label Waktu</label>
        <input type="text" name="expiry_label" :value="editPromo?.expiry_label||''" placeholder="Setiap Senin" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Warna Gradient</label>
    <select name="gradient" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        @foreach([
            'from-primary-600 to-orange-500' => '🟠 Orange',
            'from-purple-600 to-pink-500'    => '🟣 Purple-Pink',
            'from-green-600 to-teal-500'     => '🟢 Green-Teal',
            'from-blue-600 to-cyan-500'      => '🔵 Blue-Cyan',
            'from-red-600 to-rose-500'       => '🔴 Red-Rose',
            'from-yellow-500 to-amber-500'   => '🟡 Yellow-Amber',
            'from-violet-600 to-purple-500'  => '💜 Violet',
        ] as $val => $label)
        <option value="{{ $val }}" :selected="editPromo?.gradient==='{{ $val }}'">{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-slate-700/50 rounded-xl" x-data="{on:true}">
    <div>
        <p class="text-sm font-semibold text-gray-700 dark:text-slate-300">Status Aktif</p>
        <p class="text-xs text-gray-400" x-text="on ? 'Promo ditampilkan' : 'Promo disembunyikan'"></p>
    </div>
    <div>
        <input type="hidden" name="is_active" :value="on ? '1' : '0'">
        <button type="button" @click="on=!on" :class="on?'bg-emerald-500':'bg-gray-300 dark:bg-slate-600'" class="relative w-11 h-6 rounded-full transition-all duration-200">
            <span :class="on?'translate-x-5':'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></span>
        </button>
    </div>
</div>
