<x-layouts.admin title="Sirkulasi Peminjaman">

    <x-ui.header 
        title="Sirkulasi & Transaksi" 
        subtitle="Pantau peminjaman dan pengembalian buku."
        :breadcrumbs="[['label' => 'Sirkulasi']]"
    />

    <!-- Filter Card -->
    <x-ui.card class="mb-6 p-6">
        <form action="{{ route('admin.loans.index') }}" method="GET" class="space-y-4">
            @php
                $statusOptions = [
                    [
                        'value' => 'borrowed',
                        'label' => 'Dipinjam',
                        'color' => 'emerald',
                    ],
                    [
                        'value' => 'returned',
                        'label' => 'Dikembalikan',
                        'color' => 'blue',
                    ],
                ];
            @endphp

            <!-- Row 1: Status Filter -->
            <div>
                <p class="text-sm font-bold text-slate-700 mb-3">Status</p>
                <div class="flex gap-2">
                    <label class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer {{ !request('status') ? 'bg-polsri-primary/10 border-polsri-primary text-polsri-primary' : '' }}">
                        <input type="radio" name="status" value="" {{ !request('status') ? 'checked' : '' }} class="rounded-full">
                        <span class="text-sm font-medium">Semua</span>
                    </label>
                    @foreach($statusOptions as $option)
                        <label class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer {{ request('status') === $option['value'] ? 'bg-polsri-primary/10 border-polsri-primary text-polsri-primary' : '' }}">
                            <input type="radio" name="status" value="{{ $option['value'] }}" {{ request('status') === $option['value'] ? 'checked' : '' }} class="rounded-full">
                            <span class="text-sm font-medium">{{ $option['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Row 2: Filter Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Filter User dengan Search -->
                <div class="relative">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Mahasiswa</label>
                    <input type="text" id="loanUserSearch" name="user_search" placeholder="Cari nama atau NIM..." 
                        value="{{ request('user_search') }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary text-sm"
                        autocomplete="off">
                    <input type="hidden" name="user_id" id="loanUserId" value="{{ request('user_id') }}">
                    <div id="loanUserSearchResults" class="hidden absolute z-50 top-full mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
                </div>

                <!-- Filter Overdue -->
                <div class="flex flex-col">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Filter</label>
                    <label class="flex items-center gap-2 px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="overdue" value="true" {{ request('overdue') === 'true' ? 'checked' : '' }} class="rounded">
                        <span class="text-sm font-medium text-slate-700">Hanya Terlambat</span>
                    </label>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Cari Transaksi</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Transaksi, buku..." 
                        class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary text-sm">
                </div>

                <!-- Buttons -->
                <div class="flex gap-2 items-end">
                    <button type="submit" class="flex-1 px-4 py-2 bg-polsri-primary hover:bg-orange-600 text-white font-bold text-sm rounded-lg transition">
                        Terapkan
                    </button>
                    <a href="{{ route('admin.loans.index') }}" class="flex-1 px-4 py-2 border border-slate-200 text-slate-600 font-bold text-sm rounded-lg hover:bg-slate-50 transition text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </x-ui.card>

    <!-- Tambah Button -->
    <div class="mb-6">
        <a href="{{ route('admin.loans.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Peminjaman Baru
        </a>
    </div>

    <x-ui.card>
        <div id="search-results-table">
            @include('admin.circulation._table')
        </div>
    </x-ui.card>

    <!-- User Search Autocomplete Script -->
    <script>
        const userSearchInput = document.getElementById('loanUserSearch');
        const userSearchResults = document.getElementById('loanUserSearchResults');
        const userIdInput = document.getElementById('loanUserId');
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
                    onclick="selectLoanUser('${user.id}', '${user.name}')">
                    <div class="font-medium text-slate-900">${user.name}</div>
                    <div class="text-xs text-slate-500">${user.nim}</div>
                </div>
            `).join('');

            userSearchResults.classList.remove('hidden');
        });

        function selectLoanUser(id, name) {
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