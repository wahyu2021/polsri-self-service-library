@props(['title', 'author', 'image'])

<div class="snap-start shrink-0 w-32 lg:w-auto group cursor-pointer">
    <div class="aspect-[2/3] bg-slate-200 rounded-xl overflow-hidden shadow-sm relative mb-3 group-hover:shadow-md transition-all">
        @if($image)
            <img src="{{ $image }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 text-[10px]">
                <span>No Cover</span>
            </div>
        @endif
    </div>
    <h3 class="font-bold text-slate-800 text-xs line-clamp-2 leading-tight group-hover:text-polsri-primary transition">{{ $title }}</h3>
    <p class="text-[10px] text-slate-500 truncate">{{ $author }}</p>
</div>
