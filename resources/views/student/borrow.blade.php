<x-layouts.app title="Pinjam Mandiri">
    <div class="max-w-md mx-auto min-h-screen bg-white pb-24">
        
        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-100 flex items-center gap-4 sticky top-0 bg-white/80 backdrop-blur-md z-20">
            <a href="{{ route('student.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-400 hover:text-slate-800 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Scan & Borrow</h1>
        </div>

        <div class="p-6">
            
            <!-- Step 1: Scan Barcode -->
            <div id="step-scan">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a2 2 0 00-2-2h-4.586a1 1 0 01-.707-.293l-1.414-1.414a1 1 0 00-.707-.293H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4h.01M16 16h4" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Scan ISBN Buku</h2>
                    <p class="text-slate-500 text-sm mt-1">Gunakan scanner atau ketik ISBN di bawah.</p>
                </div>

                <div class="relative">
                    <input type="text" id="isbn-input" placeholder="Contoh: 978-602-000-123" 
                        class="w-full pl-12 pr-4 py-4 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 font-mono text-lg"
                        autofocus>
                    <svg class="w-6 h-6 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button onclick="checkBook()" class="absolute right-2 top-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-blue-700 transition">
                        Cek
                    </button>
                </div>

                <div id="loading-indicator" class="hidden mt-8 text-center">
                    <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>
                    <p class="text-xs text-slate-400">Mencari data buku...</p>
                </div>

                <div id="error-message" class="hidden mt-4 p-4 bg-rose-50 text-rose-600 rounded-xl text-sm text-center border border-rose-100"></div>
            </div>

            <!-- Step 2: Book Preview (Initially Hidden) -->
            <div id="step-preview" class="hidden animate-fade-in-up">
                <div class="bg-white border border-slate-100 shadow-xl rounded-2xl overflow-hidden">
                    <div class="h-48 bg-slate-100 relative">
                        <img id="preview-cover" src="" class="w-full h-full object-cover opacity-0 transition-opacity duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Stok Tersedia</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 id="preview-title" class="text-xl font-bold text-slate-900 leading-tight mb-1">Judul Buku</h3>
                        <p id="preview-author" class="text-slate-500 text-sm mb-6">Penulis</p>

                        <form method="POST" action="{{ route('student.borrow.store') }}">
                            @csrf
                            <input type="hidden" name="isbn" id="confirm-isbn">
                            
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-200 active:scale-95 transition-transform flex items-center justify-center gap-2">
                                <span>Ajukan Peminjaman</span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </form>

                        <button onclick="resetScan()" class="w-full mt-3 py-3 text-slate-400 font-medium text-sm hover:text-slate-600">Batal / Scan Ulang</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const isbnInput = document.getElementById('isbn-input');
        const loading = document.getElementById('loading-indicator');
        const errorMsg = document.getElementById('error-message');
        const stepScan = document.getElementById('step-scan');
        const stepPreview = document.getElementById('step-preview');
        
        // Handle "Enter" key on input
        isbnInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                checkBook();
            }
        });

        async function checkBook() {
            const isbn = isbnInput.value.trim();
            if(!isbn) return;

            loading.classList.remove('hidden');
            errorMsg.classList.add('hidden');
            isbnInput.disabled = true;

            try {
                const response = await fetch("{{ route('student.borrow.lookup') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ isbn: isbn })
                });

                if (!response.ok) throw new Error('Buku tidak ditemukan.');

                const data = await response.json();
                showPreview(data, isbn);

            } catch (error) {
                errorMsg.innerText = error.message;
                errorMsg.classList.remove('hidden');
                isbnInput.disabled = false;
                isbnInput.focus();
            } finally {
                loading.classList.add('hidden');
            }
        }

        function showPreview(data, isbn) {
            stepScan.classList.add('hidden');
            stepPreview.classList.remove('hidden');

            document.getElementById('preview-title').innerText = data.title;
            document.getElementById('preview-author').innerText = data.author;
            document.getElementById('confirm-isbn').value = isbn;

            const coverImg = document.getElementById('preview-cover');
            if (data.cover) {
                coverImg.src = data.cover;
                coverImg.classList.remove('opacity-0');
            } else {
                coverImg.src = ''; // Placeholder logic if needed
            }
        }

        function resetScan() {
            stepPreview.classList.add('hidden');
            stepScan.classList.remove('hidden');
            isbnInput.value = '';
            isbnInput.disabled = false;
            isbnInput.focus();
        }
    </script>
</x-layouts.app>
