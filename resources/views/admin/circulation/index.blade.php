<x-layouts.admin title="Sirkulasi Peminjaman">

    <x-ui.header 
        title="Sirkulasi & Transaksi" 
        subtitle="Pantau peminjaman dan pengembalian buku."
        :breadcrumbs="[['label' => 'Sirkulasi']]"
    >

        <form action="{{ route('admin.loans.index') }}" method="GET" class="flex gap-3">
            @php
                $statusOptions = [
                    [
                        'value' => 'borrowed',
                        'label' => 'Dipinjam',
                        'color' => 'emerald',
                    ],
                    [
                        'value' => 'returned',
                        'label' => 'Kembali',
                        'color' => 'blue',
                    ],
                ];
            @endphp

            <x-ui.filter-dropdown name="status" label="Semua Status" :options="$statusOptions" />

            <x-ui.search name="search" :value="request('search')" placeholder="Cari transaksi, peminjam, buku..." :suggestionsUrl="route('admin.loans.index')" />
        </form>

        <a href="{{ route('admin.loans.create') }}"
            class="flex items-center gap-2 px-5 py-2.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Peminjaman Baru
        </a>
    </x-ui.header>

    <x-ui.card>
        <div id="search-results-table">
            @include('admin.circulation._table')
        </div>
    </x-ui.card>

</x-layouts.admin>