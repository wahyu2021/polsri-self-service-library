<x-layouts.admin title="Manajemen Buku">

    <!-- Header & Actions -->
    <x-ui.header title="Katalog Buku" subtitle="Kelola inventaris dan stok buku perpustakaan.">
        <!-- Search Form -->
        <form action="{{ route('admin.books.index') }}" method="GET">
            <x-ui.search name="search" :value="request('search')" placeholder="Cari judul, ISBN, penulis..." :suggestionsUrl="route('admin.books.index')" />
        </form>

        <x-ui.link-button :href="route('admin.books.create')" icon="plus">
            Tambah Buku
        </x-ui.link-button>
    </x-ui.header>

    @if(session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @endif

    <!-- Books Table -->
    <x-ui.card>
        <div id="search-results-table">
            @include('admin.management-book._table')
        </div>
    </x-ui.card>

</x-layouts.admin>