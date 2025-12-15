@props(['fineData'])

@if($fineData['has_unpaid'] || $fineData['has_overdue'])
    @if($fineData['has_unpaid'])
        <!-- Unpaid Fine Alert -->
        <div class="bg-gradient-to-br from-rose-50 to-orange-50 border-l-4 border-rose-500 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-slate-900 text-sm mb-1">Denda Belum Lunas</h3>
                    <p class="text-2xl font-bold text-rose-600 mb-2">
                        Rp {{ number_format($fineData['unpaid_total'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-600 mb-3">{{ $fineData['unpaid_count'] }} transaksi menunggu pembayaran</p>
                    <a href="{{ route('student.collection.index') }}" 
                        class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-700 transition">
                        Lihat Detail
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @elseif($fineData['has_overdue'])
        <!-- Overdue Warning (Not Yet Returned) -->
        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 9v2m0 4v2m0 5v2m0-6V9m0 0a9 9 0 1018 0 9 9 0 00-18 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-slate-900 text-sm mb-1">Peminjaman Terlambat</h3>
                    <p class="text-sm text-amber-700 font-medium mb-2">
                        {{ $fineData['overdue_count'] }} buku terlambat dikembalikan
                    </p>
                    <p class="text-xs text-slate-600 mb-3">Denda akan dihitung saat buku dikembalikan</p>
                    <a href="{{ route('student.collection.index') }}" 
                        class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:text-amber-700 transition">
                        Kembalikan Sekarang
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif
@else
    <!-- No Fine Status -->
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-slate-900 text-sm mb-1">Status Denda</h3>
                <p class="text-sm text-emerald-600 font-medium">Tidak ada denda yang belum dibayar</p>
                @if($fineData['paid_count'] > 0)
                    <p class="text-xs text-slate-500 mt-1">{{ $fineData['paid_count'] }} denda sudah lunas</p>
                @endif
            </div>
        </div>
    </div>
@endif

