@props(['name' => 'search', 'value' => '', 'placeholder' => 'Cari data...'])

<div class="relative group">
    <input type="text" 
        name="{{ $name }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}" 
        {{ $attributes->merge(['class' => 'pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary w-full sm:w-64 transition-all shadow-sm group-hover:border-slate-300']) }}
    >
    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5 pointer-events-none group-hover:text-slate-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
</div>
