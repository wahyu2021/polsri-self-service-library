<x-layouts.admin title="Catat Peminjaman">

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.loans.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:border-slate-300 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Catat Peminjaman</h1>
                <p class="text-slate-500 text-sm mt-1">Buat transaksi peminjaman buku manual.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <form action="{{ route('admin.loans.store') }}" method="POST" class="p-8">
                @csrf

                <div class="space-y-6">
                    
                    <!-- Alert Info -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-bold mb-1">Ketentuan Peminjaman:</p>
                            <ul class="list-disc list-inside space-y-0.5 opacity-90">
                                <li>Durasi peminjaman default adalah <strong>7 Hari</strong>.</li>
                                <li>Denda keterlambatan <strong>Rp 1.000 / hari</strong>.</li>
                                <li>Pastikan kartu mahasiswa valid sebelum meminjamkan.</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Mahasiswa</label>
                        <select name="user_id" id="user_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary bg-white transition cursor-pointer" required>
                            <option value="">-- Cari Nama Mahasiswa --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->nim }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="book_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Buku</label>
                        <select name="book_id" id="book_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary bg-white transition cursor-pointer" required>
                            <option value="">-- Cari Judul Buku --</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} — Stok: {{ $book->stock }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1.5">* Hanya menampilkan buku dengan stok tersedia.</p>
                        @error('book_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-10 pt-6 border-t border-slate-50 flex justify-end gap-4">
                     <a href="{{ route('admin.loans.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-polsri-primary hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
                        Proses Peminjaman
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-layouts.admin>
