@props(['type' => 'success', 'message'])

@php
    $colors = match($type) {
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'error', 'danger' => 'bg-rose-50 text-rose-700 border-rose-100',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-100',
        'info' => 'bg-blue-50 text-blue-700 border-blue-100',
        default => 'bg-slate-50 text-slate-700 border-slate-100',
    };

    $icon = match($type) {
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'error', 'danger' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        default => '',
    };
@endphp

@if($message)
<div {{ $attributes->merge(['class' => "mb-6 p-4 rounded-xl text-sm font-medium border flex items-center gap-3 $colors"]) }}>
    @if($icon)
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            {!! $icon !!}
        </svg>
    @endif
    <span>{{ $message }}</span>
</div>
@endif
