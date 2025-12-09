@props(['disabled' => false, 'label' => '', 'name', 'type' => 'text', 'placeholder' => ''])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
    @endif
    
    <input 
        id="{{ $name }}" 
        type="{{ $type }}" 
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => 'w-full px-4 py-3 rounded-xl border bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition-all duration-200 ease-in-out sm:text-sm disabled:bg-slate-100 disabled:cursor-not-allowed ' . ($errors->has($name) ? 'border-danger focus:ring-danger/20' : 'border-slate-200 hover:border-slate-300')]) !!}
    >

    @error($name)
        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
    @enderror
</div>
