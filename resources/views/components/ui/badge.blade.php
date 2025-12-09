@props(['color' => 'slate', 'pulse' => false])

@php
    $colors = match($color) {
        'success', 'emerald' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'danger', 'rose', 'red' => 'bg-rose-100 text-rose-700 border-rose-200',
        'warning', 'amber', 'orange' => 'bg-amber-100 text-amber-700 border-amber-200',
        'info', 'blue' => 'bg-blue-100 text-blue-700 border-blue-200',
        'purple' => 'bg-purple-100 text-purple-700 border-purple-200',
        'slate', 'gray' => 'bg-slate-100 text-slate-600 border-slate-200',
        default => 'bg-slate-100 text-slate-600 border-slate-200',
    };
    
    $animation = $pulse ? 'animate-pulse' : '';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border $colors $animation"]) }}>
    {{ $slot }}
</span>
