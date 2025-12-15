@forelse($validationQueue as $loan)
<tr class="group hover:bg-slate-50 transition-colors">
    <td class="px-6 py-4 align-top">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs mt-0.5">
                {{ substr($loan->user->name, 0, 1) }}
            </div>
            <div>
                <div class="font-bold text-slate-900">{{ $loan->user->name }}</div>
                <div class="text-xs text-slate-500 font-mono">{{ $loan->user->nim }}</div>
                <div class="mt-1 text-[10px] text-slate-400">
                    Request: {{ $loan->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 align-top">
        <div>
            <div class="font-medium text-slate-800 line-clamp-2">{{ $loan->book->title }}</div>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="text-[10px] font-mono bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded border border-slate-200">
                    {{ $loan->book->isbn }}
                </span>
                <span class="text-[10px] text-slate-400">
                    Stok: {{ $loan->book->stock }}
                </span>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 align-top text-right">
        <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?');">
            @csrf
            @method('PUT')
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all hover:shadow hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                APPROVE
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="px-6 py-16 text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h4 class="text-slate-900 font-bold text-sm">Tidak Ada Antrean</h4>
            <p class="text-slate-500 text-xs mt-1 max-w-xs">Semua permintaan peminjaman telah divalidasi. Kerja bagus!</p>
        </div>
    </td>
</tr>
@endforelse