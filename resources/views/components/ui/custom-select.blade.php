@props([
    'label' => '', 
    'name', 
    'value' => '', 
    'options' => [], // Format: [['value' => 'val', 'label' => 'Label', 'color' => 'color']]
    'placeholder' => 'Pilih salah satu'
])

<div class="mb-4" 
    x-data="{
        open: false,
        selected: '{{ old($name, $value) }}',
        init() {
            // Pastikan value yang terpilih valid dari options
            if (!this.selected && {{ count($options) }} > 0) {
                // Opsional: set default value jika kosong
            }
        },
        get currentOption() {
            return {{ json_encode($options) }}.find(o => o.value == this.selected) || {};
        }
    }"
>
    @if($label)
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }}</label>
    @endif

    <div class="relative">
        <input type="hidden" name="{{ $name }}" x-model="selected">

        <button type="button" @click="open = !open" @click.outside="open = false"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl border bg-white transition-all duration-200 ease-in-out text-left"
            :class="open ? 'ring-2 ring-polsri-primary/20 border-polsri-primary' : ({{ $errors->has($name) ? 'true' : 'false' }} ? 'border-red-500 focus:ring-red-500/20' : 'border-slate-200 hover:border-slate-300')"
        >
            <div class="flex items-center gap-2">
                <template x-if="currentOption.color">
                    <span class="w-2 h-2 rounded-full" :class="`bg-${currentOption.color}-500`"></span>
                </template>
                
                <span class="text-sm font-medium" 
                    :class="selected ? 'text-slate-900' : 'text-slate-400'" 
                    x-text="currentOption.label || '{{ $placeholder }}'">
                </span>
            </div>

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
            class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 ring-1 ring-black/5 overflow-hidden origin-top-left focus:outline-none"
            style="display: none;">
            
            <div class="p-1 max-h-60 overflow-y-auto">
                @foreach($options as $option)
                <button type="button" 
                    @click="selected = '{{ $option['value'] }}'; open = false"
                    class="w-full text-left flex items-center justify-between px-3 py-2.5 text-sm rounded-lg hover:bg-slate-50 transition group"
                    :class="selected == '{{ $option['value'] }}' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                    
                    <div class="flex items-center gap-2">
                        @if(isset($option['color']))
                            <span class="w-2 h-2 rounded-full bg-{{ $option['color'] }}-500"></span>
                        @endif
                        <span>{{ $option['label'] }}</span>
                    </div>

                    <svg x-show="selected == '{{ $option['value'] }}'" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>