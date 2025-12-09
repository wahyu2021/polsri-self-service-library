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
        {!! $attributes->merge(['class' => 'w-full px-4 py-2.5 rounded-lg border text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-polsri-primary focus:border-transparent transition duration-200 ease-in-out sm:text-sm disabled:bg-slate-100 disabled:cursor-not-allowed ' . ($errors->has($name) ? 'border-danger focus:ring-danger' : 'border-slate-300')]) !!}
    >

    @error($name)
        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
    @enderror
</div>
