<x-layouts.admin title="Edit Peminjaman">

    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.loans.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:border-slate-300 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Transaksi</h1>
            <p class="text-slate-500 text-sm mt-1">Koreksi data peminjaman #{{ $loan->transaction_code }}</p>
        </div>
    </div>

    <form action="{{ route('admin.loans.update', $loan) }}" method="POST" x-data="{ isLoading: false }" @submit="isLoading = true">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Info Card (Read Only) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User Info -->
                <x-ui.card class="p-6">
                    <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Peminjam
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-lg">
                            {{ substr($loan->user->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-slate-900 truncate">{{ $loan->user->name }}</p>
                            <p class="text-sm text-slate-500 font-mono">{{ $loan->user->nim }}</p>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Book Info -->
                <x-ui.card class="p-6">
                    <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        Buku
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Judul</p>
                            <p class="font-bold text-slate-900 leading-snug">{{ $loan->book->title }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Penulis</p>
                            <p class="text-sm text-slate-700">{{ $loan->book->author }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">ISBN</p>
                            <p class="text-sm font-mono bg-slate-100 inline-block px-2 py-1 rounded">{{ $loan->book->isbn }}</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Edit Form -->
            <div class="lg:col-span-2">
                <x-ui.card class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status Peminjaman</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(\App\Enums\LoanStatus::cases() as $status)
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="status" value="{{ $status->value }}" class="peer sr-only" 
                                        {{ old('status', $loan->status->value) == $status->value ? 'checked' : '' }}>
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 text-center text-sm font-medium text-slate-600 transition-all peer-checked:border-polsri-primary peer-checked:text-polsri-primary peer-checked:bg-orange-50 hover:bg-slate-50">
                                        {{ ucfirst($status->value) }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('status') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Borrow Date -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pinjam</label>
                            <input type="date" name="borrow_date" value="{{ old('borrow_date', $loan->borrow_date->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition">
                            @error('borrow_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jatuh Tempo</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $loan->due_date->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition">
                            @error('due_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 border-t border-slate-100 my-2"></div>

                        <!-- Return Date -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Kembali (Aktual)</label>
                            <input type="date" name="return_date" value="{{ old('return_date', $loan->return_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition">
                            <p class="text-xs text-slate-400 mt-1.5">Kosongkan jika buku belum dikembalikan.</p>
                            @error('return_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Fine Amount -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Denda (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-slate-400 font-bold">Rp</span>
                                <input type="number" name="fine_amount" value="{{ old('fine_amount', $loan->fine_amount ?? 0) }}"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition font-mono">
                            </div>
                            @error('fine_amount') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror

                            <!-- Is Fine Paid -->
                            <div class="mt-3 flex items-center gap-2">
                                <input type="hidden" name="is_fine_paid" value="0">
                                <input type="checkbox" id="is_fine_paid" name="is_fine_paid" value="1" 
                                    class="w-4 h-4 rounded text-polsri-primary focus:ring-polsri-primary border-gray-300"
                                    {{ old('is_fine_paid', $loan->is_fine_paid) ? 'checked' : '' }}>
                                <label for="is_fine_paid" class="text-sm text-slate-700 font-medium cursor-pointer">
                                    Tandai Denda Lunas
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-50">
                        <a href="{{ route('admin.loans.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition">
                            Batal
                        </a>
                        <button type="submit" :disabled="isLoading" class="px-6 py-2.5 rounded-xl bg-polsri-primary text-white font-bold text-sm hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none flex items-center gap-2">
                            <span x-show="!isLoading">Simpan Perubahan</span>
                            <span x-show="isLoading" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>

                </x-ui.card>
            </div>
        </div>
    </form>
</x-layouts.admin>
