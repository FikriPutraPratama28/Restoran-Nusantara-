@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
    class="mb-5 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 px-5 py-3.5 rounded-2xl">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span class="text-sm font-medium">{{ session('success') }}</span>
    <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-700 text-lg leading-none">✕</button>
</div>
@endif
@if($errors->any())
<div class="mb-5 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 px-5 py-3.5 rounded-2xl">
    <p class="text-sm font-semibold mb-1">⚠️ Ada kesalahan:</p>
    @foreach($errors->all() as $e)
    <p class="text-sm">• {{ $e }}</p>
    @endforeach
</div>
@endif
