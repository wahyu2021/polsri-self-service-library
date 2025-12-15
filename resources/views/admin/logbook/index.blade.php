<x-layouts.admin title="Logbook Pengunjung">

    <x-ui.header 
        title="Logbook Pengunjung" 
        subtitle="Rekapitulasi riwayat kehadiran mahasiswa di perpustakaan."
        :breadcrumbs="[['label' => 'Logbook']]"
    />

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
        <form action="{{ route('admin.logbooks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            <!-- Search -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Cari Mahasiswa</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Nama atau NIM..." 
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:border-polsri-primary focus:ring-0 transition">
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" 
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:border-polsri-primary focus:ring-0 transition">
            </div>

            <!-- Filter Button -->
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-polsri-primary hover:bg-orange-700 text-white text-sm font-bold rounded-lg transition-all shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Waktu Masuk</th>
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Lokasi (GPS)</th>
                        <th class="px-6 py-4">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logbooks as $log)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 align-middle whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">{{ $log->check_in_time->format('H:i') }}</span>
                                <span class="text-xs text-slate-500">{{ $log->check_in_time->translatedFormat('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs border border-slate-200">
                                    {{ substr($log->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $log->user->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $log->user->nim }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            @if($log->latitude && $log->longitude)
                                <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition text-xs font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lihat Peta
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada data GPS</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                QR Scan
                            </span>
                        </td>
                    </tr>
                    @empty
                        <x-ui.empty-state colspan="4" message="Tidak ada data logbook" 
                            submessage="Coba ubah tanggal atau kata kunci pencarian." />
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logbooks->withQueryString()->links() }}
        </div>
    </div>

</x-layouts.admin>
