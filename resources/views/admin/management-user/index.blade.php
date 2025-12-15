<x-layouts.admin title="Manajemen Pengguna">

    <!-- Header & Actions -->
    <x-ui.header 
        title="Data Pengguna" 
        subtitle="Kelola akun Admin dan Mahasiswa."
        :breadcrumbs="[['label' => 'User']]"
    >

        <!-- Search Form -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-3">
            @php
                $roleOptions = [
                    [
                        'value' => \App\Enums\UserRole::ADMIN->value,
                        'label' => 'Admin',
                        'color' => 'purple',
                    ],
                    [
                        'value' => \App\Enums\UserRole::MAHASISWA->value,
                        'label' => 'Mahasiswa',
                        'color' => 'blue',
                    ],
                ];
            @endphp

            <x-ui.filter-dropdown name="role" label="Semua Role" :options="$roleOptions" />

            <x-ui.search name="search" :value="request('search')" placeholder="Cari nama, NIM, email..." :suggestionsUrl="route('admin.users.index')" />
        </form>

        <a href="{{ route('admin.users.create') }}"
            class="flex items-center gap-2 px-5 py-2.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </a>
    </x-ui.header>

    <!-- Users Table -->
    <x-ui.card>
        <div id="search-results-table">
            @include('admin.management-user._table')
        </div>
    </x-ui.card>

</x-layouts.admin>
