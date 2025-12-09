<x-layouts.app title="Koleksi Saya">
    <x-slot name="header">
        Koleksi Saya
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto px-6 lg:px-8">
            
            <!-- Section: Peminjaman Aktif -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-slate-900">Peminjaman Aktif</h2>
                    <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                        {{ $activeLoans->count() }} Buku
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($activeLoans as $loan)
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex gap-4 items-start">
                        <div class="w-16 h-20 bg-slate-100 rounded-lg flex-shrink-0 overflow-hidden">
                            @if($loan->book->cover)
                                <img src="{{ asset('storage/' . $loan->book->cover) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Cover</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-900 text-sm truncate">{{ $loan->book->title }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $loan->book->author }}</p>
                            
                            <div class="mt-2 flex items-center gap-2">
                                @if($loan->status === \App\Enums\LoanStatus::PENDING_VALIDATION)
                                    <span class="text-[10px] font-bold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">
                                        Menunggu Verifikasi
                                    </span>
                                @elseif(\Carbon\Carbon::now()->gt($loan->due_date))
                                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded">
                                        Terlambat {{ \Carbon\Carbon::now()->diffInDays($loan->due_date) }} Hari
                                    </span>
                                @else
                                    <span class="text-[10px] font-medium text-slate-500 bg-slate-50 px-2 py-0.5 rounded">
                                        Sisa {{ \Carbon\Carbon::now()->diffInDays($loan->due_date) }} Hari
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <p class="text-sm text-slate-500">Tidak ada buku yang sedang dipinjam.</p>
                        <a href="{{ route('student.borrow.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Pinjam Buku Sekarang
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Section: Riwayat Peminjaman -->
            <div class="mt-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-slate-900">Riwayat Peminjaman</h2>
                    {{-- <span class="text-xs font-medium bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full">
                        {{ $historyLoans->count() }} Buku
                    </span> --}}
                </div>

                <div class="space-y-4">
                    @forelse($historyLoans as $loan)
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex gap-4 items-start">
                        <div class="w-16 h-20 bg-slate-100 rounded-lg flex-shrink-0 overflow-hidden">
                            @if($loan->book->cover)
                                <img src="{{ asset('storage/' . $loan->book->cover) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Cover</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-900 text-sm truncate">{{ $loan->book->title }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $loan->book->author }}</p>
                            
                            <div class="mt-2 flex items-center gap-2">
                                @if($loan->fine_amount > 0)
                                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded">
                                        Denda: Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                                        Dikembalikan
                                    </span>
                                @endif
                                <span class="text-[10px] font-medium text-slate-500 bg-slate-50 px-2 py-0.5 rounded">
                                    {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <p class="text-sm text-slate-500">Belum ada riwayat peminjaman.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
