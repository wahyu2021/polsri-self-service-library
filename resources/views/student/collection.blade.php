<x-layouts.app title="Koleksi Saya">
    <div class="py-12 lg:pb-12 bg-slate-50 min-h-[calc(100vh-7rem)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Koleksi Buku</h1>
                <p class="text-slate-500 text-sm mt-1 md:mt-0">Kelola peminjaman dan riwayat bacaan Anda.</p>
            </div>
            
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-6 bg-polsri-primary rounded-full"></div>
                    <h2 class="font-bold text-lg text-slate-900">Sedang Dipinjam</h2>
                    <span class="text-xs font-bold bg-orange-100 text-orange-700 px-2.5 py-1 rounded-full">
                        {{ $activeLoans->count() }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($activeLoans as $loan)
                        <x-student.loan-card :loan="$loan" type="active" />
                    @empty
                        <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <p class="text-sm text-slate-500 font-medium">Tidak ada buku yang sedang dipinjam.</p>
                            <a href="{{ route('student.borrow.index') }}" class="mt-4 inline-flex items-center px-6 py-2.5 bg-polsri-primary rounded-xl font-bold text-xs text-white shadow-lg shadow-orange-500/30 hover:bg-orange-600 hover:-translate-y-0.5 transition-all">
                                Pinjam Buku Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-6 bg-slate-300 rounded-full"></div>
                    <h2 class="font-bold text-lg text-slate-900">Riwayat Bacaan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($historyLoans as $loan)
                        <div>
                            <x-student.loan-card :loan="$loan" type="history" />
                            @if($loan->fine_amount > 0)
                                <div class="mt-2 flex gap-2">
                                    @if($loan->is_fine_paid)
                                        <span class="flex-1 text-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">
                                            ✓ Denda Lunas
                                        </span>
                                    @else
                                        <span class="flex-1 text-center text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded border border-rose-100">
                                            ⚠ Belum Lunas
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-sm text-slate-400">Belum ada riwayat peminjaman.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
