@props(['links' => []])

@if(!empty($links))
<nav class="flex mb-3" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
    
    <!-- Home / Root Link -->
    <li class="inline-flex items-center">
      <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-slate-400 hover:text-polsri-primary transition-colors" title="Dashboard">
        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
        </svg>
      </a>
    </li>

    <!-- Dynamic Links -->
    @foreach($links as $link)
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-slate-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                
                @if(isset($link['url']) && !$loop->last)
                    <a href="{{ $link['url'] }}" class="ms-1 text-[11px] font-medium text-slate-500 hover:text-polsri-primary md:ms-2 transition-colors">
                        {{ $link['label'] }}
                    </a>
                @else
                    <span class="ms-1 text-[11px] font-bold text-slate-600 md:ms-2 bg-slate-100 px-2 py-0.5 rounded-md">
                        {{ $link['label'] }}
                    </span>
                @endif
            </div>
        </li>
    @endforeach
  </ol>
</nav>
@endif