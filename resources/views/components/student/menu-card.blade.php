@props(['route', 'icon', 'label', 'description', 'color' => 'orange'])

@php
    $colors = [
        'orange' => [
            'bg' => 'bg-orange-50',
            'text' => 'text-polsri-primary',
            'hover_border' => 'hover:border-orange-200',
            'hover_shadow' => 'hover:shadow-orange-500/10'
        ],
        'blue' => [
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-600',
            'hover_border' => 'hover:border-blue-200',
            'hover_shadow' => 'hover:shadow-blue-500/10'
        ]
    ];
    
    $c = $colors[$color] ?? $colors['orange'];
@endphp

<a href="{{ $route }}" class="relative overflow-hidden bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100 {{ $c['hover_border'] }} {{ $c['hover_shadow'] }} transition-all group">
    <div class="w-12 h-12 {{ $c['bg'] }} rounded-2xl flex items-center justify-center {{ $c['text'] }} mb-3 group-hover:scale-110 transition-transform">
        {{ $icon }}
    </div>
    <h3 class="font-bold text-slate-800">{{ $label }}</h3>
    <p class="text-xs text-slate-400 mt-1">{{ $description }}</p>
</a>
