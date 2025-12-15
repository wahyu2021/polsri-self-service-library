<x-layouts.admin title="Dashboard">
    
    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard</h1>
            <p class="text-slate-500 text-sm">Overview aktivitas perpustakaan hari ini.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-500 bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm mr-2">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                {{ now()->translatedFormat('d M Y') }}
            </span>

            <a href="{{ route('admin.books.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:border-orange-500 hover:text-orange-600 text-slate-700 text-sm font-bold rounded-lg transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buku
            </a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-lg transition-all shadow-lg shadow-slate-900/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Anggota
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pengunjung</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $visitorsToday ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs">
                <span class="text-emerald-600 font-bold flex items-center">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Hari ini
                </span>
                <span class="text-slate-400">Total scan masuk logbook</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sirkulasi Aktif</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $activeLoans ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs">
                <span class="text-slate-500">Buku sedang dipinjam mahasiswa</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Terlambat</p>
                    <h3 class="text-2xl font-bold text-rose-600 mt-1">{{ $overdueBooks ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs">
                <span class="text-rose-600 font-bold bg-rose-50 px-1.5 py-0.5 rounded">Action Needed</span>
                <span class="text-slate-400">Melewati tenggat waktu</span>
            </div>
        </div>
    </div>

    <!-- Layout Grid: 8 columns (Content) + 4 columns (Sidebar) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        
        <!-- Main Content (Validation Queue) -->
        <div class="xl:col-span-8 flex flex-col gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white border border-slate-200 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Validasi Exit Pass</h3>
                            <p class="text-slate-500 text-xs">Permintaan peminjaman yang menunggu persetujuan.</p>
                        </div>
                    </div>
                    <div id="validation-queue-badge">
                        @if($validationQueue->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 animate-pulse">
                                {{ $validationQueue->count() }} Pending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                Semua Beres
                            </span>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white text-slate-500 border-b border-slate-100 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-[35%]">Detail Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold w-[40%]">Buku Dipinjam</th>
                                <th class="px-6 py-4 font-semibold w-[25%] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50" id="validation-queue-body">
                            @include('admin.dashboard-partials.validation-queue-rows', ['validationQueue' => $validationQueue])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar (Activity Feed) -->
        <div class="xl:col-span-4 space-y-6">
            
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Aktivitas Terbaru</h3>
                    <a href="{{ route('admin.logbooks.index') }}" class="text-[10px] font-bold text-polsri-primary hover:underline">LIHAT SEMUA</a>
                </div>

                <div class="p-0">
                    @forelse($recentLogbooks->take(6) as $log)
                    <div class="relative flex items-start gap-4 p-4 border-b border-slate-50 hover:bg-slate-50/80 transition-colors last:border-0">
                        <!-- Status Dot -->
                        <div class="mt-1.5 w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)] flex-shrink-0"></div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $log->user->name }}</p>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $log->check_in_time->format('H:i') }}</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Scan Masuk • <span class="font-mono">{{ $log->user->nim }}</span></p>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-400 text-xs italic">
                        Belum ada aktivitas hari ini.
                    </div>
                    @endforelse
                </div>
                
                <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400">Menampilkan 6 aktivitas terakhir</p>
                </div>
            </div>

        </div>

    </div>

    <script>
        function confirmApprove(formId) {
            if(confirm('Setujui peminjaman ini?')) {
                document.getElementById(formId).submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Poll for new validation requests every 5 seconds
            setInterval(function() {
                fetch("{{ route('admin.dashboard.validation-queue') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Update the rows
                        document.getElementById('validation-queue-body').innerHTML = data.html;

                        // Update the badge
                        const badgeContainer = document.getElementById('validation-queue-badge');
                        if (data.count > 0) {
                            badgeContainer.innerHTML = `
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 animate-pulse">
                                    ${data.count} Pending
                                </span>
                            `;
                        } else {
                            badgeContainer.innerHTML = `
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    Semua Beres
                                </span>
                            `;
                        }
                    })
                    .catch(error => console.error('Error fetching validation queue:', error));
            }, 5000); // 5000ms = 5 seconds
        });
    </script>
</x-layouts.admin>