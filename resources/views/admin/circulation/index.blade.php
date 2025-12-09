<x-layouts.admin title="Sirkulasi Peminjaman">

    <!-- Header & Actions -->
    <x-ui.header title="Sirkulasi & Transaksi" subtitle="Pantau peminjaman dan pengembalian buku.">
        
        <!-- Search Form -->
        <form action="{{ route('admin.loans.index') }}" method="GET" class="flex gap-3">
            
            <!-- Custom Alpine.js Dropdown for Status -->
            <div x-data="{ 
                    open: false, 
                    status: '{{ request('status') }}',
                    label: '{{ request('status') == 'borrowed' ? 'Dipinjam' : (request('status') == 'returned' ? 'Kembali' : 'Semua Status') }}'
                }" 
                class="relative">
                
                <!-- Hidden Input for Form Submission -->
                <input type="hidden" name="status" :value="status">

                <!-- Trigger Button -->
                <button type="button" @click="open = !open" @click.outside="open = false"
                    class="flex items-center justify-between gap-2 pl-4 pr-3 py-2.5 bg-white rounded-xl border border-slate-200 text-sm font-medium text-slate-700 hover:border-slate-300 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition shadow-sm min-w-[160px]">
                    <span x-text="label"></span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-50 mt-2 w-full min-w-[180px] bg-white rounded-xl shadow-xl border border-slate-100 ring-1 ring-black/5 overflow-hidden origin-top-left focus:outline-none"
                    style="display: none;">
                    
                    <div class="p-1">
                        <!-- Option: Semua -->
                        <button type="button" @click="status = ''; label = 'Semua Status'; open = false; $el.closest('form').submit()"
                            class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                            :class="status === '' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                            <span>Semua Status</span>
                            <svg x-show="status === ''" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>

                        <!-- Option: Dipinjam -->
                        <button type="button" @click="status = 'borrowed'; label = 'Dipinjam'; open = false; $el.closest('form').submit()"
                            class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                            :class="status === 'borrowed' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Dipinjam</span>
                            </div>
                            <svg x-show="status === 'borrowed'" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>

                        <!-- Option: Kembali -->
                        <button type="button" @click="status = 'returned'; label = 'Kembali'; open = false; $el.closest('form').submit()"
                            class="w-full text-left flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-slate-50 transition group"
                            :class="status === 'returned' ? 'bg-slate-50 text-polsri-primary font-semibold' : 'text-slate-700'">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Kembali</span>
                            </div>
                            <svg x-show="status === 'returned'" class="w-4 h-4 text-polsri-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari transaksi..." 
                    class="pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary w-full sm:w-64 transition-all shadow-sm group-hover:border-slate-300">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5 pointer-events-none group-hover:text-slate-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </form>

        <a href="{{ route('admin.loans.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Peminjaman Baru
        </a>
    </x-ui.header>

    @if(session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @endif
    
    @if(session('error'))
        <x-ui.alert type="error" :message="session('error')" />
    @endif

    <!-- Loans Table -->
    <x-ui.card>
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
                            <div class="text-slate-900 max-w-[200px] truncate" title="{{ $loan->book->title }}">{{ $loan->book->title }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $loan->book->isbn }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-600 text-xs">
                                <span class="block">Pinjam: {{ $loan->borrow_date->format('d M Y') }}</span>
                                <span class="block {{ $loan->status == 'borrowed' && \Carbon\Carbon::now()->gt($loan->due_date) ? 'text-rose-600 font-bold' : '' }}">
                                    Jatuh Tempo: {{ $loan->due_date->format('d M Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($loan->status == 'returned')
                                <x-ui.badge color="slate">Dikembalikan</x-ui.badge>
                                @if($loan->fine_amount > 0)
                                    <div class="text-[10px] text-rose-600 mt-1 font-bold">Denda: Rp {{ number_format($loan->fine_amount) }}</div>
                                @endif
                            @elseif($loan->status == 'pending_validation')
                                <x-ui.badge color="amber">Menunggu Validasi</x-ui.badge>
                            @else
                                @if(\Carbon\Carbon::now()->gt($loan->due_date))
                                    <x-ui.badge color="rose" pulse>Terlambat</x-ui.badge>
                                @else
                                    <x-ui.badge color="emerald">Dipinjam</x-ui.badge>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($loan->status == 'borrowed')
                                <form action="{{ route('admin.loans.return', $loan) }}" method="POST" onsubmit="return confirm('Proses pengembalian buku ini? Denda akan dihitung otomatis jika ada.');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 text-slate-600 text-xs font-bold rounded-lg transition shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Kembalikan
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">Belum ada riwayat transaksi</p>
                                    <p class="text-xs mt-1">Buat peminjaman baru untuk memulai.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $loans->withQueryString()->links() }}
        </div>
    </x-ui.card>

</x-layouts.admin>
