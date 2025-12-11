<x-layouts.admin title="Catat Peminjaman">

    <script>
        window.AdminConfig = {
            searchUserUrl: "{{ route('admin.loans.searchUser') }}",
            searchBookUrl: "{{ route('admin.loans.searchBook') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    
    @vite(['resources/js/admin/loan-create.js'])

    <div class="max-w-5xl mx-auto">
        <!-- Header with Back Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.loans.index') }}" class="group w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:border-slate-300 hover:shadow-md transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Catat Peminjaman</h1>
                    <p class="text-slate-500 text-sm mt-1">Gunakan kamera atau scanner untuk memproses transaksi.</p>
                </div>
            </div>
        </div>

        <x-ui.card>
            <form action="{{ route('admin.loans.store') }}" method="POST" id="loanForm" class="p-6 md:p-8">
                @csrf
                
                <input type="hidden" name="user_id" id="final_user_id" required>
                <input type="hidden" name="book_id" id="final_book_id" required>

                <!-- Scanner Container -->
                <div id="scanner_container" class="hidden mb-8 p-6 bg-slate-900 rounded-2xl relative border border-slate-800 shadow-inner">
                    <div class="absolute top-4 left-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Camera Feed</div>
                    <div id="reader" class="w-full max-w-sm mx-auto overflow-hidden rounded-xl border-2 border-slate-700 shadow-2xl"></div>
                    
                    <button type="button" onclick="stopScanner()" class="absolute top-4 right-4 text-slate-400 hover:text-white bg-slate-800 hover:bg-rose-600 rounded-lg p-2 transition-all duration-200 shadow-lg border border-slate-700 hover:border-rose-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    
                    <p class="text-center text-slate-400 text-sm mt-4 font-medium">Arahkan kamera ke QR Code / Barcode</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 relative">
                    <!-- Vertical Divider (Desktop) -->
                    <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-px bg-slate-100 -translate-x-1/2"></div>

                    <!-- Step 1: User Data -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-2 border-b border-slate-50">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 font-bold text-sm shadow-sm ring-2 ring-blue-50">1</span>
                            <h3 class="font-bold text-slate-800 text-lg">Data Peminjam</h3>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Scan QR / Ketik NIM</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="input_nim" autocomplete="off"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-0 focus:border-blue-500 transition-all font-mono uppercase placeholder:normal-case placeholder:font-sans placeholder:text-slate-400" 
                                        placeholder="Contoh: 062030xxx" autofocus>
                                    
                                    <!-- Suggestions Dropdown -->
                                    <div id="student_suggestions" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-60 overflow-y-auto"></div>
                                </div>
                                
                                <button type="button" id="btn_scan_student" onclick="startScanner('student')" 
                                    class="group w-12 shrink-0 flex items-center justify-center bg-white text-slate-500 hover:bg-blue-600 hover:text-white border border-slate-200 hover:border-blue-600 rounded-xl transition-all duration-200 relative overflow-hidden shadow-sm" title="Scan dengan Kamera">
                                    
                                    <svg class="icon-off w-6 h-6 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <svg class="icon-on w-6 h-6 hidden animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>

                                <button type="button" onclick="searchStudent()" 
                                    class="px-6 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 font-bold text-sm transition-all shadow-md shadow-slate-900/10 hover:shadow-lg hover:shadow-slate-900/20 active:transform active:scale-95">
                                    Cari
                                </button>
                            </div>
                            <p id="error_student" class="hidden text-xs text-rose-500 font-bold mt-2 ml-1"></p>
                        </div>

                        <div id="student_result" class="hidden transition-all duration-300">
                            <!-- Populated by JS -->
                            <div class="flex items-start gap-4 p-4 bg-blue-50/50 border border-blue-100 rounded-xl relative group hover:border-blue-200 transition-colors">
                                <div class="w-14 h-14 rounded-full bg-slate-200 overflow-hidden flex-shrink-0 border-4 border-white shadow-sm">
                                    <img src="{{ asset('images/default-profile.jpg') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0 pt-1">
                                    <h4 class="font-bold text-slate-900 truncate text-lg leading-tight" id="res_student_name">-</h4>
                                    <p class="text-sm text-slate-500 font-mono mt-1" id="res_student_nim">-</p>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Mahasiswa Aktif
                                        </span>
                                    </div>
                                </div>
                                <button type="button" onclick="resetStudent()" class="absolute top-3 right-3 p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Book Data -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-2 border-b border-slate-50">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-orange-100 text-orange-600 font-bold text-sm shadow-sm ring-2 ring-orange-50">2</span>
                            <h3 class="font-bold text-slate-800 text-lg">Data Buku</h3>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Scan Barcode / ISBN</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <input type="text" id="input_isbn" autocomplete="off"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-0 focus:border-orange-500 transition-all font-mono placeholder:font-sans placeholder:text-slate-400" 
                                        placeholder="Scan ISBN / Kode Buku...">
                                    
                                    <!-- Suggestions Dropdown -->
                                    <div id="book_suggestions" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-60 overflow-y-auto"></div>
                                </div>

                                <button type="button" id="btn_scan_book" onclick="startScanner('book')" 
                                    class="group w-12 shrink-0 flex items-center justify-center bg-white text-slate-500 hover:bg-orange-600 hover:text-white border border-slate-200 hover:border-orange-600 rounded-xl transition-all duration-200 relative overflow-hidden shadow-sm" title="Scan dengan Kamera">
                                    
                                    <svg class="icon-off w-6 h-6 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <svg class="icon-on w-6 h-6 hidden animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>

                                <button type="button" onclick="searchBook()" 
                                    class="px-6 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 font-bold text-sm transition-all shadow-md shadow-slate-900/10 hover:shadow-lg hover:shadow-slate-900/20 active:transform active:scale-95">
                                    Cari
                                </button>
                            </div>
                            <p id="error_book" class="hidden text-xs text-rose-500 font-bold mt-2 ml-1"></p>
                        </div>

                        <div id="book_result" class="hidden transition-all duration-300">
                            <!-- Populated by JS -->
                            <div class="p-5 bg-orange-50/50 border border-orange-100 rounded-xl relative hover:border-orange-200 transition-colors">
                                <h4 class="font-bold text-slate-900 text-lg leading-snug line-clamp-2" id="res_book_title">-</h4>
                                <div class="flex items-center gap-3 mt-3 text-sm text-slate-500">
                                    <span class="font-mono bg-white border border-slate-200 px-2 py-1 rounded text-xs text-slate-600" id="res_book_isbn">-</span>
                                    <div class="flex items-center gap-1.5 truncate">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        <span id="res_book_author" class="truncate">-</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Stok Tersedia
                                    </span>
                                </div>
                                <button type="button" onclick="resetBook()" class="absolute top-3 right-3 p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-6 border-t border-slate-50 flex justify-end gap-3">
                    <a href="{{ route('admin.loans.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all">
                        Batal
                    </a>
                    <button type="submit" id="btn_submit" disabled 
                        class="px-8 py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-sm cursor-not-allowed transition-all flex items-center gap-2 shadow-none hover:shadow-lg disabled:hover:shadow-none disabled:opacity-70">
                        <span>Simpan Transaksi</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-layouts.admin>