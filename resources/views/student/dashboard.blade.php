<x-layouts.app title="Dashboard">
    <div class="pb-24">
        
        <!-- Header & Greetings -->
        <div class="bg-gradient-to-r from-blue-900 to-slate-900 text-white pt-8 pb-12 px-6 rounded-b-[2rem] shadow-lg mb-6 relative overflow-hidden">
            <!-- Decorative Circles -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-500/20 rounded-full blur-xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-blue-200 text-sm mb-1">Selamat Datang,</p>
                    <h1 class="text-2xl font-bold leading-tight">{{ explode(' ', $user->name)[0] }}! 👋</h1>
                </div>
                <a href="{{ route('student.profile.index') }}" class="block">
                    <div class="w-10 h-10 rounded-full bg-white/20 border border-white/30 flex items-center justify-center backdrop-blur-sm">
                        <span class="text-sm font-bold">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                </a>
            </div>

            <!-- Status Alerts -->
            @php
                $overdueCount = $activeLoans->filter(fn($l) => \Carbon\Carbon::now()->gt($l->due_date))->count();
                $fineTotal = $activeLoans->sum('fine_amount') + $historyLoans->where('status', 'returned')->where('fine_amount', '>', 0)->sum('fine_amount'); 
                // Note: Logic denda bisa lebih kompleks, ini simplifikasi visual
            @endphp

            @if($overdueCount > 0)
            <div class="mt-6 bg-rose-500/20 border border-rose-500/30 backdrop-blur-md rounded-xl p-3 flex items-center gap-3 animate-pulse">
                <div class="bg-rose-500 p-1.5 rounded-full text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-rose-100">Ada {{ $overdueCount }} buku terlambat!</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Actions (Menu Pintas) -->
        <div class="px-6 -mt-16 relative z-20 grid grid-cols-2 gap-4">
            <!-- Logbook / Check-in -->
            <a href="{{ route('student.logbook.index') }}" class="bg-white p-5 rounded-2xl shadow-lg shadow-slate-200 active:scale-95 transition-transform duration-100 group">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a2 2 0 00-2-2h-4.586a1 1 0 01-.707-.293l-1.414-1.414a1 1 0 00-.707-.293H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4h.01M16 16h4" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Scan Masuk</h3>
                <p class="text-xs text-slate-400 mt-1">Isi logbook kehadiran</p>
            </a>

            <!-- Borrow / Pinjam -->
            <a href="{{ route('student.borrow.index') }}" class="bg-white p-5 rounded-2xl shadow-lg shadow-slate-200 active:scale-95 transition-transform duration-100 group">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Pinjam Buku</h3>
                <p class="text-xs text-slate-400 mt-1">Self-service tanpa antre</p>
            </a>
        </div>

        <!-- External Inspiration Widget (Google Books) -->
        <div class="px-6 mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-slate-900 text-lg">Inspirasi Hari Ini 💡</h2>
                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded">via Google Books</span>
            </div>

            <div class="flex gap-4 overflow-x-auto pb-4 -mx-6 px-6 scrollbar-hide">
                @forelse($recommendations as $item)
                    @php
                        $vol = $item['volumeInfo'];
                        $img = $vol['imageLinks']['thumbnail'] ?? null;
                    @endphp
                    <div class="w-32 flex-shrink-0">
                        <div class="aspect-[2/3] bg-slate-200 rounded-lg overflow-hidden shadow-sm mb-2 relative">
                            @if($img)
                                <img src="{{ str_replace('http://', 'https://', $img) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Cover</div>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-800 text-xs line-clamp-2 leading-tight">{{ $vol['title'] ?? 'No Title' }}</h3>
                        <p class="text-[10px] text-slate-500 mt-1 truncate">{{ $vol['authors'][0] ?? 'Unknown' }}</p>
                    </div>
                @empty
                    <div class="w-full text-center py-4 bg-slate-50 rounded-lg border border-dashed text-slate-400 text-sm">
                        Gagal memuat rekomendasi. Cek koneksi internet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Menu Lainnya -->
        <div class="px-6 mt-2">
            <h2 class="font-bold text-slate-900 text-lg mb-4">Menu Lainnya</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 divide-y divide-slate-100">
                <a href="{{ route('student.collection.index') }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900">Koleksi Saya</h3>
                        <p class="text-xs text-slate-500">Lihat peminjaman aktif & riwayat</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
                
                <a href="{{ route('student.profile.index') }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition">
                    <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900">Profil Saya</h3>
                        <p class="text-xs text-slate-500">Data diri & pengaturan</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

    </div>
</x-layouts.app>
