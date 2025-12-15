        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Transaksi</th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50/50 transition group">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-slate-700">{{ $loan->transaction_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $loan->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $loan->user->nim }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 max-w-[200px] truncate" title="{{ $loan->book->title }}">
                                    {{ $loan->book->title }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $loan->book->isbn }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-600 text-xs">
                                    <span class="block">Pinjam: {{ $loan->borrow_date->format('d M Y') }}</span>
                                    <span
                                        class="block {{ $loan->status === \App\Enums\LoanStatus::BORROWED && \Carbon\Carbon::now()->gt($loan->due_date) ? 'text-rose-600 font-bold' : '' }}">
                                        Jatuh Tempo: {{ $loan->due_date->format('d M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($loan->status === \App\Enums\LoanStatus::RETURNED)
                                    <div class="flex justify-center mb-2">
                                        <x-ui.badge color="slate">Dikembalikan</x-ui.badge>
                                    </div>
                                    @if ($loan->fine_amount > 0)
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="text-[10px] font-mono font-bold text-slate-700">Denda: Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</div>
                                            <div class="flex justify-center">
                                                @if ($loan->is_fine_paid)
                                                    <x-ui.badge color="emerald">Lunas</x-ui.badge>
                                                @else
                                                    <x-ui.badge color="rose">Belum Lunas</x-ui.badge>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-[10px] text-slate-500">Tanpa denda</div>
                                    @endif
                                @elseif($loan->status === \App\Enums\LoanStatus::PENDING_VALIDATION)
                                    <div class="flex justify-center">
                                        <x-ui.badge color="amber">Menunggu Validasi</x-ui.badge>
                                    </div>
                                @else
                                    @if (\Carbon\Carbon::now()->gt($loan->due_date))
                                        <div class="flex justify-center">
                                            <x-ui.badge color="rose" pulse>Terlambat</x-ui.badge>
                                        </div>
                                    @else
                                        <div class="flex justify-center">
                                            <x-ui.badge color="emerald">Dipinjam</x-ui.badge>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.loans.edit', $loan) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Data Manual">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    @if ($loan->status === \App\Enums\LoanStatus::PENDING_VALIDATION)
                                    <form action="{{ route('admin.loans.approve', $loan) }}" method="POST"
                                        onsubmit="return confirmAction(event, 'Setujui peminjaman ini? Pastikan buku fisik sesuai dengan data.', 'success')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-sm shadow-emerald-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            Setujui Keluar
                                        </button>
                                    </form>
                                @elseif ($loan->status === \App\Enums\LoanStatus::BORROWED)
                                    <form action="{{ route('admin.loans.return', $loan) }}" method="POST"
                                        onsubmit="return confirmAction(event, 'Proses pengembalian buku ini? Denda akan dihitung otomatis jika ada.', 'info')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 text-slate-600 text-xs font-bold rounded-lg transition shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                            Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="px-3 py-1.5 bg-white border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 text-slate-600 text-xs font-bold rounded-lg transition shadow-sm">Sudah Dikembalikan</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-ui.empty-state colspan="6" message="Belum ada riwayat transaksi" 
                            submessage="Buat peminjaman baru untuk memulai." />
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $loans->withQueryString()->links() }}
        </div>
