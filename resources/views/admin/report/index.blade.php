<x-layouts.admin title="Laporan & Denda">

    <!-- Header & Filter -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Laporan Keuangan</h1>
            <p class="text-slate-500 text-sm mt-1">Rekapitulasi pendapatan denda dan keterlambatan.</p>
        </div>
        
        <form action="{{ route('admin.reports.index') }}" method="GET" class="bg-white p-2 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-2">
            <div class="flex items-center gap-2 px-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Periode</span>
            </div>
            <input type="date" name="start_date" value="{{ $startDate }}" 
                class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary">
            <span class="text-slate-400">-</span>
            <input type="date" name="end_date" value="{{ $endDate }}" 
                class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary">
            
            <button type="submit" class="px-4 py-1.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
                Terapkan
            </button>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <p class="text-slate-500 text-sm font-medium">Total Pendapatan Denda</p>
            <h3 class="text-3xl font-bold text-emerald-600 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-xs text-emerald-600/80 mt-1 font-medium bg-emerald-50 inline-block px-2 py-0.5 rounded">
                Dalam periode terpilih
            </p>
        </div>

        <!-- Transactions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <p class="text-slate-500 text-sm font-medium">Transaksi Terlambat</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-2 ml">{{ $totalTransactions }}</h3>
        </div>

        <!-- Average -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <p class="text-slate-500 text-sm font-medium">Rata-rata Denda / Transaksi</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-2">Rp {{ number_format($averageFine, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Chart Visualization (CSS Only) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-900">Grafik Tren Pendapatan</h3>
        </div>
        
        <div class="h-64 flex items-end gap-2 sm:gap-4 overflow-x-auto pb-2 custom-scrollbar">
            @if(count($chartData) > 0 && $maxChartValue > 0)
                @foreach($chartData as $data)
                    @php
                        $height = ($data['amount'] / $maxChartValue) * 100;
                        $height = $height < 5 ? 5 : $height; // Min height 5% visual
                        $isZero = $data['amount'] == 0;
                    @endphp
                    <div class="flex flex-col items-center gap-2 group min-w-[40px] flex-1">
                        <div class="relative w-full bg-slate-50 rounded-t-lg flex items-end h-48 group-hover:bg-slate-100 transition">
                            @if(!$isZero)
                            <div style="height: {{ $height }}%" 
                                class="w-full bg-polsri-primary/80 rounded-t-md group-hover:bg-polsri-primary transition-all relative">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10 pointer-events-none">
                                    Rp {{ number_format($data['amount'], 0, ',', '.') }}
                                </div>
                            </div>
                            @else
                            <div class="w-full h-1 bg-slate-200"></div>
                            @endif
                        </div>
                        <span class="text-[10px] text-slate-400 font-medium rotate-0 whitespace-nowrap">{{ $data['date'] }}</span>
                    </div>
                @endforeach
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">
                    Tidak ada data untuk ditampilkan pada grafik.
                </div>
            @endif
        </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Detailed Logs (2/3) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Rincian Pembayaran</h3>
                <button onclick="window.print()" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Laporan
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Mahasiswa</th>
                            <th class="px-6 py-3">Buku</th>
                            <th class="px-6 py-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($fines as $fine)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $fine->return_date->format('d/m/Y H:i') }}
                                <div class="font-mono text-[10px] mt-0.5">{{ $fine->transaction_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $fine->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $fine->user->nim }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-700 truncate max-w-[150px]">{{ $fine->book->title }}</div>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">
                                Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data denda pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $fines->withQueryString()->links() }}
            </div>
        </div>

        <!-- Top Violators (1/3) -->
        <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden h-fit">
            <div class="p-6 border-b border-slate-50">
                <h3 class="font-bold text-slate-900">Top Keterlambatan</h3>
                <p class="text-xs text-slate-500 mt-1">Mahasiswa dengan total denda tertinggi.</p>
            </div>
            <div class="p-4 space-y-4">
                @forelse($topViolators as $index => $violator)
                <div class="flex items-center gap-4 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                    <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-md">
                        #{{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ $violator->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $violator->total_late }}x Terlambat</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-rose-600">Rp {{ number_format($violator->total_fine, 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-sm">
                    Belum ada data analitik.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</x-layouts.admin>