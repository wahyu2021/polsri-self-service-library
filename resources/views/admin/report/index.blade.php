<x-layouts.admin title="Laporan & Denda">

    <!-- Screen Only: Web Header & Filter -->
    <x-ui.header 
        title="Laporan Keuangan" 
        subtitle="Rekapitulasi pendapatan denda dan keterlambatan."
        :breadcrumbs="[['label' => 'Laporan']]"
    >
        <form action="{{ route('admin.reports.index') }}" method="GET"
            class="bg-white p-2 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-2">
            <div class="flex items-center gap-2 px-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Periode</span>
            </div>
            <input type="date" name="start_date" value="{{ $startDate }}"
                class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary">
            <span class="text-slate-400">-</span>
            <input type="date" name="end_date" value="{{ $endDate }}"
                class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary">

            <button type="submit"
                class="px-4 py-1.5 bg-polsri-primary hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
                Terapkan
            </button>
            <button type="submit" formaction="{{ route('admin.reports.pdf') }}"
                class="px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-rose-500/20 transition-all hover:-translate-y-0.5">
                Export PDF
            </button>
            <button type="submit" formaction="{{ route('admin.reports.export') }}"
                class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5">
                Export CSV
            </button>
        </form>
    </x-ui.header>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenue -->
        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <p class="text-slate-500 text-sm font-medium">Total Pendapatan Denda</p>
            <h3 class="text-3xl font-bold text-emerald-600 mt-2">Rp
                {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p
                class="text-xs text-emerald-600/80 mt-1 font-medium bg-emerald-50 inline-block px-2 py-0.5 rounded">
                Dalam periode terpilih
            </p>
        </div>

        <!-- Transactions -->
        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <p class="text-slate-500 text-sm font-medium">Transaksi Terlambat</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-2 ml">{{ $totalTransactions }}</h3>
        </div>

        <!-- Average -->
        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <p class="text-slate-500 text-sm font-medium">Rata-rata Denda / Transaksi</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-2">Rp
                {{ number_format($averageFine, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Chart Visualization (ApexCharts) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-900">Grafik Tren Pendapatan</h3>
        </div>

        <div id="revenue-chart" class="w-full h-[350px]"></div>
    </div>

    <!-- Inject Data & Scripts -->
    <script>
        window.ReportData = {
            chartData: @json($chartData)
        };
    </script>
    @vite(['resources/js/admin/report-chart.js'])

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Detailed Logs (2/3) -->
        <div
            class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Rincian Pembayaran</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Mahasiswa</th>
                            <th class="px-6 py-3">Buku</th>
                            <th class="px-6 py-3 text-right">
                                Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($fines as $fine)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td
                                    class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $fine->return_date->format('d/m/Y H:i') }}
                                    <div class="font-mono text-[10px] mt-0.5">{{ $fine->transaction_code }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $fine->user->name }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $fine->user->nim }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="text-slate-700 truncate max-w-[150px]">
                                        {{ $fine->book->title }}</div>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-bold text-slate-900">
                                    Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-12 text-center text-slate-400">
                                    Tidak ada data denda pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Hide on Print -->
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $fines->withQueryString()->links() }}
            </div>
        </div>

        <!-- Top Violators (1/3) -->
        <div
            class="lg:col-span-1 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden h-fit">
            <div class="p-6 border-b border-slate-50">
                <h3 class="font-bold text-slate-900">Top Keterlambatan</h3>
                <p class="text-xs text-slate-500 mt-1">Mahasiswa dengan total denda tertinggi.</p>
            </div>
            <div class="p-4 space-y-4">
                @forelse($topViolators as $index => $violator)
                    <div
                        class="flex items-center gap-4 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                        <div
                            class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-md">
                            #{{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $violator->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $violator->total_late }}x Terlambat</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-rose-600">Rp
                                {{ number_format($violator->total_fine, 0, ',', '.') }}</p>
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
