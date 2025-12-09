@props(['title', 'value', 'icon', 'color' => 'blue', 'subtext' => null])

@php
    $bgIcon = match($color) {
        'blue' => 'bg-blue-50 text-blue-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'rose', 'red' => 'bg-rose-50 text-rose-600',
        'emerald', 'green' => 'bg-emerald-50 text-emerald-600',
        'purple' => 'bg-purple-50 text-purple-600',
        default => 'bg-slate-50 text-slate-600',
    };
@endphp

<div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl {{ $bgIcon }} flex items-center justify-center">
        {{ $slot }} <!-- Slot for SVG Icon -->
    </div>
    <div>
        <p class="text-slate-500 text-sm font-medium">{{ $title }}</p>
        <h3 class="text-2xl font-bold text-slate-900">{{ $value }}</h3>
        @if($subtext)
            <p class="text-xs text-slate-400 mt-0.5">{{ $subtext }}</p>
        @endif
    </div>
</div>
