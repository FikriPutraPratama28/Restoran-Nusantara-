@php $edit = $edit ?? false; @endphp

<div x-data="{previewUrl:'',handleFile(f){ if(!f)return; const r=new FileReader(); r.onload=e=>{this.previewUrl=e.target.result}; r.readAsDataURL(f); }}"
    x-init="previewUrl = editMember?.image_url || editMember?.image || ''">
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Foto Anggota</label>
    <div x-show="previewUrl" class="relative mb-3 rounded-xl overflow-hidden h-32 group">
        <img :src="previewUrl" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <button type="button" @click="$refs.fi.click()" class="bg-white text-gray-900 text-xs font-semibold px-3 py-1.5 rounded-xl">📷 Ganti</button>
        </div>
    </div>
    <div x-show="!previewUrl" @click="$refs.fi.click()" class="border-2 border-dashed border-gray-300 dark:border-slate-600 hover:border-violet-400 rounded-xl p-4 text-center cursor-pointer transition-all">
        <div class="text-2xl mb-1">👤</div>
        <p class="text-xs text-gray-500 dark:text-slate-400">Klik untuk pilih foto</p>
    </div>
    <input x-ref="fi" type="file" name="image" accept="image/*" class="hidden" @change="handleFile($event.target.files[0])">
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Atau URL Foto</label>
    <input type="url" name="image_url" :value="editMember?.image_url||''" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Nama <span class="text-red-500">*</span></label>
        <input type="text" name="name" :value="editMember?.name||''" required placeholder="Pak Budi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
        <input type="text" name="role" :value="editMember?.role||''" required placeholder="Head Chef" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Emoji (jika tanpa foto)</label>
        <input type="text" name="emoji" :value="editMember?.emoji||'👨‍🍳'" placeholder="👨‍🍳" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none text-center text-xl">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Warna Gradient</label>
        <select name="gradient" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none">
            @foreach(['from-orange-400 to-red-500'=>'🟠 Orange','from-pink-400 to-purple-500'=>'🩷 Pink','from-amber-400 to-orange-500'=>'🟡 Amber','from-blue-400 to-cyan-500'=>'🔵 Blue','from-green-400 to-teal-500'=>'🟢 Green','from-violet-400 to-purple-500'=>'💜 Violet'] as $v=>$l)
            <option value="{{ $v }}" :selected="editMember?.gradient==='{{ $v }}'">{{ $l }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Bio Singkat</label>
    <textarea name="bio" rows="2" :value="editMember?.bio||''" placeholder="Deskripsi singkat..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-violet-500 outline-none resize-none"></textarea>
</div>
