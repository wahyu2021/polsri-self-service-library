<x-layouts.admin title="Edit Buku">

    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <x-ui.header 
            title="Edit Buku" 
            subtitle="Perbarui informasi buku yang sudah ada."
            :breadcrumbs="[
                ['label' => 'Buku', 'url' => route('admin.books.index')],
                ['label' => 'Edit']
            ]"
        >
            <x-ui.link-button :href="route('admin.books.index')" color="gray" icon="arrow-left">
                Kembali
            </x-ui.link-button>
        </x-ui.header>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="p-8" x-data="{ isLoading: false }" @submit="isLoading = true">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Left Column: Details -->
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Judul Buku</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition" 
                                required>
                            @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="isbn" class="block text-sm font-semibold text-slate-700 mb-2">Nomor ISBN</label>
                            <div class="relative">
                                <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" 
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition font-mono" 
                                    required>
                                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 11h2v4H6v-4zm6 11v1m0-22v1m0 20h2v-4h-2v4zm-2-11H8v4h2v-4zm8 0h-2v4h2v-4z" />
                                </svg>
                            </div>
                            @error('isbn') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="author" class="block text-sm font-semibold text-slate-700 mb-2">Penulis / Pengarang</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition" 
                                required>
                            @error('author') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="publisher" class="block text-sm font-semibold text-slate-700 mb-2">Penerbit</label>
                                <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $book->publisher) }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition" 
                                    placeholder="Nama Penerbit">
                                @error('publisher') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="publication_year" class="block text-sm font-semibold text-slate-700 mb-2">Tahun Terbit</label>
                                <input type="number" name="publication_year" id="publication_year" value="{{ old('publication_year', $book->publication_year) }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition" 
                                    placeholder="2024">
                                @error('publication_year') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                            <select name="category" id="category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition bg-white">
                                @foreach(['Teknologi Informasi', 'Ekonomi & Bisnis', 'Teknik Mesin', 'Teknik Elektro', 'Bahasa & Sastra', 'Sains Dasar', 'Umum'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $book->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-semibold text-slate-700 mb-2">Stok Tersedia</label>
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="decrementStock()" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100 hover:border-slate-300 transition font-bold text-lg">-</button>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $book->stock) }}" min="0"
                                    class="w-24 text-center px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition font-bold text-slate-900" 
                                    required>
                                <button type="button" onclick="incrementStock()" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100 hover:border-slate-300 transition font-bold text-lg">+</button>
                            </div>
                            @error('stock') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="synopsis" class="block text-sm font-semibold text-slate-700 mb-2">Sinopsis / Ringkasan</label>
                            <textarea name="synopsis" id="synopsis" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition text-sm" placeholder="Deskripsi singkat buku...">{{ old('synopsis', $book->synopsis) }}</textarea>
                            @error('synopsis') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Right Column: Cover Upload -->
                    <div class="space-y-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Cover Buku</label>
                        
                        <div class="relative group cursor-pointer">
                            <input type="file" name="cover_image" id="cover_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" onchange="previewImage(this)">
                            
                            <div id="image-preview" class="w-full aspect-[2/3] bg-slate-50 rounded-2xl border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:border-slate-400 transition relative overflow-hidden {{ $book->cover_image ? 'border-none' : '' }}">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-medium">Klik untuk ganti cover</span>
                                @endif
                            </div>
                        </div>
                        @error('cover_image') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-10 pt-6 border-t border-slate-50 flex justify-end gap-4">
                     <a href="{{ route('admin.books.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" :disabled="isLoading" class="px-8 py-3 bg-polsri-primary hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none flex items-center gap-2">
                        <span x-show="!isLoading">Simpan Perubahan</span>
                        <span x-show="isLoading" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Script Sederhana untuk Interaksi -->
    <script>
        function incrementStock() {
            const input = document.getElementById('stock');
            input.value = parseInt(input.value) + 1;
        }

        function decrementStock() {
            const input = document.getElementById('stock');
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    preview.classList.remove('border-dashed', 'bg-slate-50');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-layouts.admin>
