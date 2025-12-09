@props(['type' => 'submit', 'fullWidth' => false, 'variant' => 'primary'])

@php
    $baseClasses = "inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed";
    
    $variants = [
        'primary' => 'bg-polsri-primary hover:bg-orange-600 text-white focus:ring-polsri-primary shadow-lg shadow-orange-500/30',
        'secondary' => 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50 focus:ring-slate-500',
        'danger' => 'bg-danger hover:bg-rose-600 text-white focus:ring-danger shadow-lg shadow-rose-500/30',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ($fullWidth ? ' w-full' : '');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
