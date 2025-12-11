@props([
    'name', 
    'label' => 'Filter', 
    'options' => [], 
    'selected' => request($name) 
])

@php
    $currentOption = collect($options)->firstWhere('value', $selected);
    $currentLabel = $currentOption['label'] ?? $label;
@endphp

<div x-data="{ 
        open: false, 
        value: '{{ $selected }}', 
        label: '{{ $currentLabel }}'
    }" 
    class="relative"
>
    <input type="hidden" name="{{ $name }}" :value="value">

    <button type="button" @click="open = !open" @click.outside="open = false"
        class="flex items-center justify-between gap-2 pl-4 pr-3 py-2.5 bg-white rounded-xl border border-slate-200 text-sm font-medium text-slate-700 hover:border-slate-300 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition shadow-sm min-w-[150px]">
        <span x-text="label"></span>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 w-full min-w-[160px] bg-white rounded-xl shadow-xl border border-slate-100 ring-1 ring-black/5 overflow-hidden origin-top-left focus:outline-none"
        style="display: none;">
        
        <div class="p-1">
            <button type="button" 
                @click="value = ''; label = '{{ $label }}'; open = false; $nextTick(() => $el.closest('form').submit())"
                class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                :class="value === '' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                <span>{{ $label }}</span>
                <svg x-show="value === ''" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>

            @foreach($options as $option)
                <button type="button" 
                    @click="value = '{{ $option['value'] }}'; label = '{{ $option['label'] }}'; open = false; $nextTick(() => $el.closest('form').submit())"
                    class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                    :class="value === '{{ $option['value'] }}' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                    
                    <div class="flex items-center gap-2">
                        @if(isset($option['color']))
                            <span class="w-2 h-2 rounded-full bg-{{ $option['color'] }}-500"></span>
                        @endif
                        <span>{{ $option['label'] }}</span>
                    </div>

                    <svg x-show="value === '{{ $option['value'] }}'" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            @endforeach
        </div>
    </div>
</div>