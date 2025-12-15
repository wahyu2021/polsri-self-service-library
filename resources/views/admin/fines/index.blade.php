<x-layouts.admin title="Manajemen Denda">

    <x-ui.header 
        title="Manajemen Denda" 
        subtitle="Pantau dan kelola pembayaran denda peminjam."
        :breadcrumbs="[['label' => 'Denda']]"
    />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Unpaid -->
        <x-ui.card class="p-6">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Belum Lunas</p>
                <p class="text-2xl font-bold text-rose-600">Rp {{ number_format($stats['unpaid_total'], 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ $stats['unpaid_count'] }} transaksi</p>
            </div>
        </x-ui.card>

        <!-- Total Paid -->
        <x-ui.card class="p-6">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Sudah Lunas</p>
                <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($stats['paid_total'], 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ $stats['paid_count'] }} transaksi</p>
            </div>
        </x-ui.card>

        <!-- Grand Total -->
        <x-ui.card class="p-6 md:col-span-2 lg:col-span-2">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Total Semua Denda</p>
                <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($stats['unpaid_total'] + $stats['paid_total'], 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ $stats['unpaid_count'] + $stats['paid_count'] }} transaksi</p>
            </div>
        </x-ui.card>
    </div>

    <!-- Tab Table Card -->
    <x-ui.card class="overflow-hidden">
        <!-- Filter Section -->
        <div class="px-6 pt-6 border-b border-slate-100 pb-6">
            <form action="{{ route('admin.fines.index') }}" method="GET" class="space-y-4">
                <!-- Row 1: Status Tab & Filter User -->
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    <div class="flex gap-2 border-b border-slate-100 pb-4 sm:pb-0">
                        <a href="{{ route('admin.fines.index', ['tab' => 'unpaid']) }}"
                            class="px-4 py-2 font-bold text-sm {{ $tab === 'unpaid' ? 'text-rose-600 border-b-2 border-rose-600 -mb-4' : 'text-slate-500 hover:text-slate-700' }} transition">
                            Belum Lunas ({{ $stats['unpaid_count'] }})
                        </a>
                        <a href="{{ route('admin.fines.index', ['tab' => 'paid']) }}"
                            class="px-4 py-2 font-bold text-sm {{ $tab === 'paid' ? 'text-emerald-600 border-b-2 border-emerald-600 -mb-4' : 'text-slate-500 hover:text-slate-700' }} transition">
                            Sudah Lunas ({{ $stats['paid_count'] }})
                        </a>
                    </div>
                </div>

                <!-- Row 2: Filter Controls -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <!-- Filter User dengan Search -->
                    <div class="relative flex-1 sm:flex-none sm:w-64">
                        <input type="text" id="fineUserSearch" name="user_search" placeholder="Cari mahasiswa..." 
                            value="{{ $userSearch }}"
                            class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary text-sm"
                            autocomplete="off">
                        <input type="hidden" name="user_id" id="fineUserId" value="{{ $userId }}">
                        <div id="fineUserSearchResults" class="hidden absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
                    </div>

                    <!-- Filter Tanggal -->
                    <div class="flex gap-2 flex-1 sm:flex-none">
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                            class="px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary text-sm flex-1">
                        <span class="text-slate-400 flex items-center">-</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                            class="px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary text-sm flex-1">
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-polsri-primary hover:bg-orange-600 text-white font-bold text-sm rounded-lg transition whitespace-nowrap">
                            Terapkan
                        </button>
                        <a href="{{ route('admin.fines.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-sm rounded-lg hover:bg-slate-50 transition whitespace-nowrap">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="px-6 py-4 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Transaksi</th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-right">Denda</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($fines as $loan)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-slate-700">{{ $loan->transaction_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $loan->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $loan->user->nim }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 max-w-[200px] truncate" title="{{ $loan->book->title }}">
                                    {{ $loan->book->title }}
                                </div>
                                <div class="text-xs text-slate-500 font-mono">{{ $loan->book->isbn }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-600 text-sm">
                                    {{ $loan->return_date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-mono font-bold text-slate-900">
                                    Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center">
                                    @if ($loan->is_fine_paid)
                                        <x-ui.badge color="emerald">Lunas</x-ui.badge>
                                    @else
                                        <x-ui.badge color="rose">Belum Lunas</x-ui.badge>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($loan->is_fine_paid)
                                        <form action="{{ route('admin.fines.markUnpaid', $loan) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Tandai denda belum lunas? Ini akan mengubah status pembayaran.')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                                                Tandai Belum Lunas
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.fines.markPaid', $loan) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Tandai denda sudah lunas? Konfirmasi pembayaran dari mahasiswa terlebih dahulu.')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs font-bold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                                Tandai Lunas
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.loans.edit', $loan) }}"
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit Transaksi">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <p class="font-bold">Tidak ada denda {{ $tab === 'paid' ? 'yang sudah lunas' : 'yang belum lunas' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($fines->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $fines->withQueryString()->links() }}
            </div>
        @endif
    </x-ui.card>

    <!-- User Search Autocomplete Script -->
    <script>
        const userSearchInput = document.getElementById('fineUserSearch');
        const userSearchResults = document.getElementById('fineUserSearchResults');
        const userIdInput = document.getElementById('fineUserId');
        const users = @json($users);

        userSearchInput?.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            if (query.length < 1) {
                userSearchResults.classList.add('hidden');
                return;
            }

            const filtered = users.filter(user => 
                user.name.toLowerCase().includes(query) || 
                user.nim.includes(query)
            );

            if (filtered.length === 0) {
                userSearchResults.innerHTML = '<div class="px-4 py-3 text-sm text-slate-500">Tidak ada hasil</div>';
                userSearchResults.classList.remove('hidden');
                return;
            }

            userSearchResults.innerHTML = filtered.map(user => `
                <div class="px-4 py-2 hover:bg-slate-100 cursor-pointer border-b border-slate-100 last:border-b-0"
                    onclick="selectFineUser('${user.id}', '${user.name}')">
                    <div class="font-medium text-slate-900">${user.name}</div>
                    <div class="text-xs text-slate-500">${user.nim}</div>
                </div>
            `).join('');

            userSearchResults.classList.remove('hidden');
        });

        function selectFineUser(id, name) {
            userIdInput.value = id;
            userSearchInput.value = name;
            userSearchResults.classList.add('hidden');
        }

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!userSearchInput?.contains(e.target) && !userSearchResults?.contains(e.target)) {
                userSearchResults?.classList.add('hidden');
            }
        });
    </script>

</x-layouts.admin>
