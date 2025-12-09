<x-layouts.admin title="Manajemen Buku">

    <!-- Header & Actions -->
    <x-ui.header title="Katalog Buku" subtitle="Kelola inventaris dan stok buku perpustakaan.">
        <!-- Search Form -->
        <form action="{{ route('admin.books.index') }}" method="GET">
            <x-ui.search name="search" :value="request('search')" placeholder="Cari judul, ISBN, penulis..." />
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
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Buku Info</th>
                        <th class="px-6 py-4">ISBN</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4 text-center">Stok</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($books as $book)
                    <tr class="hover:bg-slate-50/50 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 rounded-md bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center text-slate-300">
                                    @if($book->cover)
                                        <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 line-clamp-1 group-hover:text-polsri-primary transition-colors">{{ $book->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">Ditambahkan {{ $book->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600">
                            {{ $book->isbn }}
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            {{ $book->author }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($book->stock > 5)
                                <x-ui.badge color="emerald">{{ $book->stock }} Tersedia</x-ui.badge>
                            @elseif($book->stock > 0)
                                <x-ui.badge color="amber">{{ $book->stock }} Menipis</x-ui.badge>
                            @else
                                <x-ui.badge color="rose">Habis</x-ui.badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.books.edit', $book) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <x-ui.empty-state colspan="5" message="Belum ada buku" submessage="Mulai dengan menambahkan buku baru ke inventaris." />
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $books->withQueryString()->links() }}
        </div>
    </x-ui.card>

</x-layouts.admin>