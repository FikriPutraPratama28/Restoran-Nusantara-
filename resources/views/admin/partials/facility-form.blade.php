@php $edit = $edit ?? false; @endphp

<div x-data="{previewUrl:'',isDragging:false,
    handleFile(f){ if(!f||!f.type.startsWith('image/'))return; const r=new FileReader(); r.onload=e=>{this.previewUrl=e.target.result}; r.readAsDataURL(f); },
    handleDrop(e){ this.isDragging=false; this.handleFile(e.dataTransfer.files[0]); }
}" x-init="previewUrl = editFacility?.image_url || editFacility?.image || ''">
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Foto Fasilitas</label>
    <div x-show="previewUrl" class="relative mb-3 rounded-xl overflow-hidden h-36 group">
        <img :src="previewUrl" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
            <button type="button" @click="$refs.fi.click()" class="bg-white text-gray-900 text-xs font-semibold px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow">
                <svg class="w-3.5 h-3.5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
                <span>Ganti</span>
            </button>
            <button type="button" @click="previewUrl=''" class="bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Hapus</span>
            </button>
        </div>
    </div>
    <div x-show="!previewUrl" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="handleDrop($event)" @click="$refs.fi.click()"
        :class="isDragging?'border-violet-500 bg-violet-50':'border-gray-300 dark:border-slate-600 hover:border-violet-400'"
        class="border-2 border-dashed rounded-xl p-5 text-center cursor-pointer transition-all">
        <div class="w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center mx-auto mb-2 text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-slate-400">Klik atau drag gambar (maks 10MB)</p>
        <button type="button" class="mt-2 bg-violet-600 text-white text-xs px-4 py-1.5 rounded-lg">Pilih Gambar</button>
    </div>
    <input x-ref="fi" type="file" name="image" accept="image/*" class="hidden" @change="handleFile($event.target.files[0])">
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Atau URL Gambar</label>
    <input type="url" name="image_url" :value="editFacility?.image_url||''" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Icon</label>
        <select name="icon" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
            <option value="ac" :selected="editFacility?.icon==='ac' || editFacility?.icon==='❄️'">Air Conditioner (AC)</option>
            <option value="garden" :selected="editFacility?.icon==='garden' || editFacility?.icon==='🌿'">Taman Outdoor</option>
            <option value="wifi" :selected="editFacility?.icon==='wifi' || editFacility?.icon==='📶'">WiFi Internet</option>
            <option value="parking" :selected="editFacility?.icon==='parking' || editFacility?.icon==='🅿️'">Area Parkir</option>
            <option value="music" :selected="editFacility?.icon==='music' || editFacility?.icon==='🎵'">Live Musik</option>
            <option value="kids" :selected="editFacility?.icon==='kids' || editFacility?.icon==='👶'">Playground Anak</option>
            <option value="private" :selected="editFacility?.icon==='private' || editFacility?.icon==='room' || editFacility?.icon==='🎂'">Ruang Privat VIP</option>
            <option value="wheelchair" :selected="editFacility?.icon==='wheelchair' || editFacility?.icon==='♿'">Akses Kursi Roda</option>
            <option value="security" :selected="editFacility?.icon==='security' || editFacility?.icon==='🔒'">CCTV Keamanan</option>
            <option value="building" :selected="!editFacility || editFacility?.icon==='building' || editFacility?.icon==='🏢'">Default (Gedung)</option>
        </select>
    </div>
    <div class="col-span-2">
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Judul <span class="text-red-500">*</span></label>
        <input type="text" name="title" :value="editFacility?.title||''" required placeholder="Ruang Ber-AC" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
    <textarea name="description" rows="2" :value="editFacility?.description||''" placeholder="Deskripsi fasilitas..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none resize-none"></textarea>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tag / Kategori</label>
    <input type="text" name="tag" :value="editFacility?.tag||''" placeholder="Indoor, Outdoor, VIP..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
</div>

<div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-slate-700/50 rounded-xl" x-data="{on:true}">
    <div>
        <p class="text-sm font-semibold text-gray-700 dark:text-slate-300">Status Aktif</p>
        <p class="text-xs text-gray-400" x-text="on ? 'Ditampilkan di website' : 'Disembunyikan'"></p>
    </div>
    <div>
        <input type="hidden" name="is_active" :value="on ? '1' : '0'">
        <button type="button" @click="on=!on" :class="on?'bg-emerald-500':'bg-gray-300 dark:bg-slate-600'" class="relative w-11 h-6 rounded-full transition-all duration-200">
            <span :class="on?'translate-x-5':'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></span>
        </button>
    </div>
</div>
