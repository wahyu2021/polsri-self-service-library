<x-layouts.admin title="Dashboard">
    
    <!-- Header Section -->
    <x-ui.header title="Dashboard Overview" subtitle="Pantau aktivitas perpustakaan secara real-time.">
        <div class="flex items-center gap-2 text-sm font-medium text-slate-500 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </x-ui.header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-ui.stats-card title="Pengunjung Hari Ini" :value="$visitorsToday ?? 0" color="blue">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </x-ui.stats-card>

        <x-ui.stats-card title="Buku Sedang Dipinjam" :value="$activeLoans ?? 0" color="orange">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </x-ui.stats-card>

        <x-ui.stats-card title="Terlambat Kembali" :value="$overdueBooks ?? 0" color="rose">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-ui.stats-card>
    </div>

    <!-- Main Content Area: Split View -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Validation Queue (2/3 width) -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            <!-- Queue Section -->
            <x-ui.card>
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900">Antrean Validasi (Exit Pass)</h3>
                    <x-ui.badge color="orange">{{ $validationQueue->count() }} Pending</x-ui.badge>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Mahasiswa</th>
                                <th class="px-6 py-3">Judul Buku</th>
                                <th class="px-6 py-3">Waktu Request</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($validationQueue as $loan)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $loan->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $loan->user->nim }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-700 truncate max-w-[200px]">{{ $loan->book->title }}</div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $loan->book->isbn }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $loan->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Tolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <button class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Setujui">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm">Tidak ada antrean validasi saat ini.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

        </div>

        <!-- Right Column: Live Feed (1/3 width) -->
        <div class="lg:col-span-1">
            <x-ui.card class="h-full max-h-[600px] flex flex-col">
                <div class="p-6 border-b border-slate-50">
                    <h3 class="font-bold text-slate-900">Live Absensi</h3>
                    <p class="text-xs text-slate-500 mt-1">Real-time QR Scan Feed</p>
                </div>

                <div class="overflow-y-auto flex-1 p-4 space-y-4 custom-scrollbar">
                    @forelse($recentLogbooks as $log)
                    <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm flex-shrink-0">
                            {{ substr($log->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $log->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $log->check_in_time->format('H:i:s') }} • {{ $log->user->nim }}</p>
                            
                            <div class="mt-1.5 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">GPS Valid</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        Belum ada aktivitas scan.
                    </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

    </div>

</x-layouts.admin>