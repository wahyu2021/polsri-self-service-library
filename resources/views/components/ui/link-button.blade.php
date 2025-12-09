@props(['href', 'icon' => 'plus'])

@php
    $icons = [
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />',
        'edit' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />',
    ];
    
    $svg = $icons[$icon] ?? $icon; // Use preset or raw svg path
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-2 px-5 py-2.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5']) }}>
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        {!! $svg !!}
    </svg>
    {{ $slot }}
</a>
