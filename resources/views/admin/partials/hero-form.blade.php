@php $edit = $edit ?? false; @endphp

{{-- Upload / Preview --}}
<div x-data="{previewUrl:'',isDragging:false,
    handleFile(f){ if(!f||!f.type.startsWith('image/'))return; const r=new FileReader(); r.onload=e=>{this.previewUrl=e.target.result}; r.readAsDataURL(f); },
    handleDrop(e){ this.isDragging=false; this.handleFile(e.dataTransfer.files[0]); }
}" x-init="previewUrl = {{ $edit ? "editSlide?.image_url || editSlide?.image || ''" : "''" }}">
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Gambar Background</label>
    <div x-show="previewUrl" class="relative mb-3 rounded-xl overflow-hidden h-36 group border border-slate-700">
        <img :src="previewUrl" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <button type="button" @click="$refs.fi.click()" class="bg-white text-gray-900 text-xs font-semibold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow">
                <svg class="w-3.5 h-3.5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
                <span>Ganti</span>
            </button>
        </div>
    </div>
    <div x-show="!previewUrl" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="handleDrop($event)" @click="$refs.fi.click()"
        :class="isDragging?'border-violet-500 bg-violet-50':'border-gray-300 dark:border-slate-600 hover:border-violet-400'"
        class="border-2 border-dashed rounded-xl p-5 text-center cursor-pointer transition-all bg-slate-950/20">
        <div class="w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center mx-auto mb-2 text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-slate-400">Klik atau drag gambar (PNG/JPG, maks 10MB)</p>
        <button type="button" class="mt-2 bg-violet-600 text-white text-xs px-4 py-1.5 rounded-lg flex items-center gap-1.5 mx-auto">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span>Pilih Gambar</span>
        </button>
    </div>
    <input x-ref="fi" type="file" name="image" accept="image/*" class="hidden" @change="handleFile($event.target.files[0])">
</div>

{{-- URL Gambar --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Atau URL Gambar</label>
    <input type="url" name="image_url" :value="editSlide?.image_url || ''" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
</div>

{{-- Tipe Media --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Tipe Media</label>
    <select name="media_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        <option value="image" :selected="!editSlide || editSlide.media_type==='image'">Gambar</option>
        <option value="video" :selected="editSlide?.media_type==='video'">Video</option>
    </select>
</div>

{{-- Video URL --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">URL Video (jika tipe video)</label>
    <input type="url" name="video_url" :value="editSlide?.video_url || ''" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
</div>

{{-- Title --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Judul <span class="text-red-500">*</span></label>
    <input type="text" name="title" :value="editSlide?.title || ''" required placeholder="Cita Rasa Nusantara..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
</div>

{{-- Subtitle & Description --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Subtitle</label>
        <input type="text" name="subtitle" :value="editSlide?.subtitle || ''" placeholder="Buka Sekarang..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Teks Tombol CTA</label>
        <input type="text" name="cta_text" :value="editSlide?.cta_text || 'Lihat Menu'" placeholder="Lihat Menu" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
    <textarea name="description" rows="2" :value="editSlide?.description || ''" placeholder="Deskripsi singkat..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none resize-none"></textarea>
</div>

{{-- Status --}}
<div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-slate-700/50 rounded-xl" x-data="{on:true}">
    <div>
        <p class="text-sm font-semibold text-gray-700 dark:text-slate-300">Status Aktif</p>
        <p class="text-xs text-gray-400" x-text="on ? 'Slide ditampilkan' : 'Slide disembunyikan'"></p>
    </div>
    <div>
        <input type="hidden" name="is_active" :value="on ? '1' : '0'">
        <button type="button" @click="on=!on" :class="on?'bg-emerald-500':'bg-gray-300 dark:bg-slate-600'" class="relative w-11 h-6 rounded-full transition-all duration-200">
            <span :class="on?'translate-x-5':'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></span>
        </button>
    </div>
</div>
