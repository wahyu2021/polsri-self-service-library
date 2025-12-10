@props(['loan', 'type' => 'active'])

@php
    $isOverdue = \Carbon\Carbon::now()->gt($loan->due_date);
    $daysRemaining = \Carbon\Carbon::now()->diffInDays($loan->due_date);
    $isPending = $loan->status === \App\Enums\LoanStatus::PENDING_VALIDATION;
@endphp

<div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all group flex gap-5 items-start">
    <div class="w-20 h-28 bg-slate-100 rounded-xl flex-shrink-0 overflow-hidden shadow-inner relative {{ $type === 'history' ? 'grayscale group-hover:grayscale-0 transition-all' : '' }}">
        @if($loan->book->cover)
            <img src="{{ asset('storage/' . $loan->book->cover) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
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
                            Terlambat {{ $daysRemaining }} Hari
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                            Sisa {{ $daysRemaining }} Hari
                        </span>
                    @endif
                @else
                    <div class="flex flex-wrap gap-2">
                        @if($loan->fine_amount > 0)
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">
                                Denda: Rp{{ number_format($loan->fine_amount/1000, 0) }}k
                            </span>
                        @else
                            <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                                Tepat Waktu
                            </span>
                        @endif
                        <span class="text-[10px] text-slate-400">
                            {{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/y') }}
                        </span>
                    </div>
                @endif
            </div>
            
            @if($type === 'active' && $isPending)
                <a href="{{ route('student.ticket.show', $loan->id) }}" class="block mt-2 text-[10px] text-center font-bold text-polsri-primary bg-orange-50 py-1 rounded hover:bg-orange-100 transition">
                    Lihat Tiket
                </a>
            @endif
        </div>
    </div>
</div>