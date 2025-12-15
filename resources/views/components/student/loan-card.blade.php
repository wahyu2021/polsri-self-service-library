@props(['loan', 'type' => 'active'])

@php
    $now = \Carbon\Carbon::now()->startOfDay();
    $dueDate = $loan->due_date->startOfDay();
    
    $isOverdue = $now->gt($dueDate);
    $isToday = $now->eq($dueDate);
    $daysRemaining = $now->diffInDays($dueDate);
    
    $isPending = $loan->status === \App\Enums\LoanStatus::PENDING_VALIDATION;
@endphp

<div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all group flex gap-5 items-start">
    <div class="w-20 h-28 bg-slate-100 rounded-xl flex-shrink-0 overflow-hidden shadow-inner relative {{ $type === 'history' ? 'grayscale group-hover:grayscale-0 transition-all' : '' }}">
        @if($loan->book->cover_image)
            <img src="{{ asset('storage/' . $loan->book->cover_image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs text-center p-2">No Cover</div>
        @endif

        @if($type === 'active' && $isOverdue)
            <div class="absolute inset-0 bg-rose-500/80 flex items-center justify-center">
                <span class="text-white text-[10px] font-bold uppercase tracking-wider">Overdue</span>
            </div>
        @endif
    </div>

    <div class="flex-1 min-w-0 flex flex-col h-28 justify-between">
        <div>
            <h3 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2 mb-1 group-hover:text-polsri-primary transition-colors">
                {{ $loan->book->title }}
            </h3>
            <p class="text-xs text-slate-500 truncate">{{ $loan->book->author }}</p>
        </div>
        
        <div>
            <div class="flex items-center justify-between mt-2">
                @if($type === 'active')
                    @if($isPending)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Verifikasi
                        </span>
                    @elseif($isOverdue)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Terlambat {{ $daysRemaining }} Hari
                        </span>
                    @elseif($isToday)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-orange-50 text-orange-600 border border-orange-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Tenggat Hari Ini
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                            Sisa {{ $daysRemaining }} Hari
                        </span>
                    @endif
                @else
                    <div class="flex flex-wrap gap-2">
                        @if($loan->fine_amount > 0)
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">
                                Denda: Rp{{ number_format($loan->fine_amount/1000, 0) }}k
                            </span>
                        @else
                            <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">
                                Tepat Waktu
                            </span>
                        @endif
                        <span class="text-[10px] text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">
                            {{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/y') }}
                        </span>
                    </div>
                @endif
            </div>
            
            @if($type === 'active' && $isPending)
                <a href="{{ route('student.ticket.show', $loan->id) }}" class="block mt-2 text-[10px] text-center font-bold text-polsri-primary bg-blue-50 py-1 rounded hover:bg-blue-100 transition">
                    Lihat Tiket
                </a>
            @endif
        </div>
    </div>
</div>