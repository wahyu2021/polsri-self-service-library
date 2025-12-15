@props(['title', 'subtitle' => null, 'breadcrumbs' => []])

<div class="mb-8">
    @if(!empty($breadcrumbs))
        <x-ui.breadcrumbs :links="$breadcrumbs" />
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-slate-500 text-sm mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        
        @if($slot->isNotEmpty())
            <div class="flex items-center gap-3">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
