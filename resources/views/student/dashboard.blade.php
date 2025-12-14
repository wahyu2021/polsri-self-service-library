<x-layouts.app title="Dashboard">
    <div class="max-w-7xl mx-auto w-full">

        <div class="flex flex-col lg:flex-row lg:gap-8 lg:p-8">

            <div class="lg:w-1/3 xl:w-1/4">
                <x-student.identity-card :user="$user" :activeLoans="$activeLoans" />
            </div>

            <div class="lg:w-2/3 xl:w-3/4 p-6 lg:p-0 mt-4 lg:mt-0">

                <h2 class="font-bold text-slate-800 text-lg mb-4 hidden lg:block">Akses Cepat</h2>
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <x-student.menu-card route="{{ route('student.logbook.index') }}" label="Scan Logbook"
                        description="Masuk perpustakaan" color="orange">
                        <x-slot name="icon">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                        </x-slot>
                    </x-student.menu-card>

                    <x-student.menu-card route="{{ route('student.borrow.index') }}" label="Pinjam Buku"
                        description="Self-service scan" color="blue">
                        <x-slot name="icon">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </x-slot>
                    </x-student.menu-card>
                </div>

                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-slate-800 text-lg">Inspirasi Hari Ini</h2>
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded">Google Books</span>
                    </div>

                    <div
                        class="flex lg:grid lg:grid-cols-4 gap-4 overflow-x-auto pb-4 -mx-6 px-6 lg:mx-0 lg:px-0 lg:overflow-visible no-scrollbar snap-x snap-mandatory">
                        @forelse($recommendations as $item)
                            @php
                                $vol = $item['volumeInfo'];
                                $img = $vol['imageLinks']['thumbnail'] ?? null;
                                $img = $img ? str_replace('http://', 'https://', $img) : null;
                            @endphp
                            <x-student.recommendation-card :title="$vol['title'] ?? 'Judul'" :author="$vol['authors'][0] ?? 'Penulis'" :image="$img" />
                        @empty
                            <div
                                class="w-full col-span-4 py-8 text-center text-slate-400 border border-dashed rounded-xl">
                                Gagal memuat rekomendasi.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
