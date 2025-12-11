<x-layouts.admin title="Manajemen Buku">

    <!-- Header & Actions -->
    <x-ui.header title="Katalog Buku" subtitle="Kelola inventaris dan stok buku perpustakaan.">
        <!-- Search Form -->
        <form action="{{ route('admin.books.index') }}" method="GET" class="flex gap-3">
            @php
                $categoryOptions = $categories->map(function($cat) {
                    return [
                        'value' => $cat->id,
                        'label' => $cat->name,
                        'color' => 'blue',
                    ];
                })->toArray();
            @endphp

            <x-ui.filter-dropdown name="category_id" label="Semua Kategori" :options="$categoryOptions" />

            <x-ui.search name="search" :value="request('search')" placeholder="Cari judul, ISBN, penulis..." :suggestionsUrl="route('admin.books.index')" />
        </form>

        <x-ui.link-button :href="route('admin.books.create')" icon="plus">
            Tambah Buku
        </x-ui.link-button>
    </x-ui.header>

    <!-- Books Table -->
    <x-ui.card>
        <div id="search-results-table">
            @include('admin.management-book._table')
        </div>
    </x-ui.card>

</x-layouts.admin>