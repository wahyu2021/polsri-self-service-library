@props(['colspan' => 1, 'message' => 'Tidak ada data ditemukan.', 'submessage' => null])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-12 text-center text-slate-400">
        <div class="flex flex-col items-center gap-3">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <div>
                <p class="font-medium text-slate-900">{{ $message }}</p>
                @if($submessage)
                    <p class="text-xs mt-1">{{ $submessage }}</p>
                @endif
            </div>
        </div>
    </td>
</tr>
