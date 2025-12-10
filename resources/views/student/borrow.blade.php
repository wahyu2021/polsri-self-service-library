<x-layouts.app title="Pinjam Mandiri">

    <div class="bg-white lg:bg-slate-50 lg:py-12 min-h-[calc(100vh-8rem)]">
        <div class="max-w-xl mx-auto bg-white lg:rounded-3xl lg:shadow-xl lg:overflow-hidden min-h-[80vh]">
            
            <div class="px-6 py-6 border-b border-gray-100 flex items-center gap-4 sticky top-0 bg-white/80 backdrop-blur-md z-20">
                <a href="{{ route('student.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-400 hover:text-slate-800 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="text-xl font-bold text-slate-900">Scan & Borrow</h1>
            </div>

            <div class="p-6">
                <div id="step-scan">
                
                <div id="scanner-container" class="hidden mb-6 relative rounded-2xl overflow-hidden bg-black shadow-lg">
                    <div id="reader" class="w-full"></div>
                    <button onclick="stopScanner()" class="absolute top-2 right-2 bg-black/50 text-white p-2 rounded-full z-10">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <div class="absolute bottom-4 left-0 right-0 text-center text-white/80 text-xs pointer-events-none">
                        Arahkan kamera ke Barcode Buku
                    </div>
                </div>

                <div class="text-center mb-8" id="scan-illustration">
                    <div class="w-20 h-20 bg-orange-50 text-orange-600 rounded-3xl flex items-center justify-center mx-auto mb-4 cursor-pointer hover:bg-orange-100 transition" onclick="startScanner()">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a2 2 0 00-2-2h-4.586a1 1 0 01-.707-.293l-1.414-1.414a1 1 0 00-.707-.293H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4h.01M16 16h4" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Scan ISBN Buku</h2>
                    <p class="text-slate-500 text-sm mt-1">Ketuk ikon di atas untuk buka kamera.</p>
                </div>

                <div class="relative" id="manual-input-container">
                    <input type="text" id="isbn-input" placeholder="Ketik ISBN manual..." 
                        class="w-full pl-12 pr-4 py-4 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-orange-500 font-mono text-lg"
                        autofocus>
                    <svg class="w-6 h-6 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button onclick="checkBook()" class="absolute right-2 top-2 bg-polsri-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-orange-600 transition">
                        Cek
                    </button>
                </div>

                <div id="loading-indicator" class="hidden mt-8 text-center">
                    <div class="w-8 h-8 border-4 border-orange-200 border-t-polsri-primary rounded-full animate-spin mx-auto mb-2"></div>
                    <p class="text-xs text-slate-400">Mencari data buku...</p>
                </div>

                <div id="error-message" class="hidden mt-4 p-4 bg-rose-50 text-rose-600 rounded-xl text-sm text-center border border-rose-100"></div>
            </div>

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
                            
                            <button type="submit" class="w-full bg-polsri-primary hover:bg-orange-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-orange-200 active:scale-95 transition-transform flex items-center justify-center gap-2">
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
        window.AppConfig = {
            lookupUrl: "{{ route('student.borrow.lookup') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    @vite(['resources/js/student/borrow.js'])
</x-layouts.app>