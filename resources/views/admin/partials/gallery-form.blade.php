@php $edit = $edit ?? null; @endphp

<div class="space-y-3">
    <div>
        <label class="text-sm font-medium">Judul</label>
        <input type="text" name="title" :value="editMember?.title || '{{ addslashes(old('title', '')) }}'" value="{{ old('title', '') }}" class="input w-full">
    </div>
    <div>
        <label class="text-sm font-medium">Caption</label>
        <textarea name="caption" class="input w-full" rows="3" x-bind:value="editMember?.caption || '{{ addslashes(old('caption', '')) }}'">{{ old('caption', '') }}</textarea>
    </div>
    <div>
        <label class="text-sm font-medium">Gambar (upload)</label>
        <input type="file" name="image" accept="image/*" class="mt-2 block">
        <template x-if="editMember?.image || editMember?.image_url">
            <div class="mt-2 text-xs text-gray-500">Gambar saat ini: <span x-text="editMember?.title || ''"></span></div>
            <label class="inline-flex items-center mt-1">
                <input type="checkbox" name="remove_image" value="1" class="mr-2"> Hapus gambar
            </label>
        </template>
    </div>
    <div>
        <label class="text-sm font-medium">Atau URL Gambar</label>
        <input type="text" name="image_url" :value="editMember?.image_url || '{{ addslashes(old('image_url', '')) }}'" value="{{ old('image_url', '') }}" class="input w-full">
    </div>
    <div class="flex items-center gap-3">
        <label class="inline-flex items-center">
            <input type="checkbox" name="is_active" value="1" :checked="editMember ? editMember.is_active : {{ old('is_active', true) ? 'true' : 'false' }}" {{ old('is_active', true) ? 'checked' : '' }} class="mr-2"> Aktif
        </label>
    </div>
</div>
