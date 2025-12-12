<x-layouts.admin title="Laporan & Denda">

    <!-- Print Only: Formal Header (Kop Surat) -->
    <div class="hidden print:block mb-8 text-black font-serif">
        <div class="flex items-center border-b-2 border-black pb-4 mb-4 gap-4">
            <!-- Logo Polsri (Placeholder or Image) -->
            <div class="w-24 h-24 flex items-center justify-center">
                <!-- Replace src with actual Polsri logo path if available -->
                <img src="{{ asset('images/logo-polsri.png') }}" alt="Logo Polsri" class="w-20 h-auto">
            </div>
            <div class="flex-1 text-center">
                <h2 class="text-sm uppercase font-bold tracking-wide">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN
                    TEKNOLOGI</h2>
                <h1 class="text-xl uppercase font-bold tracking-wider my-1">POLITEKNIK NEGERI SRIWIJAYA</h1>
                <h3 class="text-lg uppercase font-bold">UPT PERPUSTAKAAN</h3>
                <p class="text-xs mt-1">Jalan Srijaya Negara Bukit Besar, Palembang 30139</p>
                <p class="text-xs">Telepon: (0711) 353414 | Laman: www.polsri.ac.id</p>
            </div>
        </div>

        <div class="text-center mb-6">
            <h2 class="text-lg font-bold underline uppercase mb-1">LAPORAN KEUANGAN & DENDA PERPUSTAKAAN</h2>
            <p class="text-sm">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s.d.
                {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
        </div>
    </div>

    <!-- Screen Only: Web Header & Filter -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8 print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Laporan Keuangan</h1>
            <p class="text-slate-500 text-sm mt-1">Rekapitulasi pendapatan denda dan keterlambatan.</p>
        </div>

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
            <button type="submit" formaction="{{ route('admin.reports.export') }}"
                class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5">
                Export CSV
            </button>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 print:grid-cols-3 print:gap-4">
        <!-- Revenue -->
        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] print:border-black print:border print:rounded-none">
            <p class="text-slate-500 text-sm font-medium print:text-black">Total Pendapatan Denda</p>
            <h3 class="text-3xl font-bold text-emerald-600 mt-2 print:text-black">Rp
                {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p
                class="text-xs text-emerald-600/80 mt-1 font-medium bg-emerald-50 inline-block px-2 py-0.5 rounded print:hidden">
                Dalam periode terpilih
            </p>
        </div>

        <!-- Transactions -->
        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] print:border-black print:border print:rounded-none">
            <p class="text-slate-500 text-sm font-medium print:text-black">Transaksi Terlambat</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-2 ml print:text-black">{{ $totalTransactions }}</h3>
        </div>

        <!-- Average -->
        <div
            class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] print:border-black print:border print:rounded-none">
            <p class="text-slate-500 text-sm font-medium print:text-black">Rata-rata Denda / Transaksi</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-2 print:text-black">Rp
                {{ number_format($averageFine, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Chart Visualization (ApexCharts) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8 print:hidden">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 print:block">

        <!-- Detailed Logs (2/3) -->
        <div
            class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden print:w-full print:border-black print:border print:rounded-none print:shadow-none mb-8">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between print:border-black print:p-4">
                <h3 class="font-bold text-slate-900 print:text-black">Rincian Pembayaran</h3>
                <button onclick="window.print()"
                    class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1 print:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Laporan
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100 print:bg-white print:text-black print:border-black">
                        <tr>
                            <th class="px-6 py-3 print:px-2 print:py-2 print:border print:border-black">Tanggal</th>
                            <th class="px-6 py-3 print:px-2 print:py-2 print:border print:border-black">Mahasiswa</th>
                            <th class="px-6 py-3 print:px-2 print:py-2 print:border print:border-black">Buku</th>
                            <th class="px-6 py-3 text-right print:px-2 print:py-2 print:border print:border-black">
                                Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 print:divide-black">
                        @forelse($fines as $fine)
                            <tr class="hover:bg-slate-50/50 transition print:hover:bg-transparent">
                                <td
                                    class="px-6 py-4 text-slate-500 text-xs print:px-2 print:py-2 print:border print:border-black print:text-black">
                                    {{ $fine->return_date->format('d/m/Y H:i') }}
                                    <div class="font-mono text-[10px] mt-0.5">{{ $fine->transaction_code }}</div>
                                </td>
                                <td class="px-6 py-4 print:px-2 print:py-2 print:border print:border-black">
                                    <div class="font-medium text-slate-900 print:text-black">{{ $fine->user->name }}
                                    </div>
                                    <div class="text-xs text-slate-500 print:text-black">{{ $fine->user->nim }}</div>
                                </td>
                                <td class="px-6 py-4 print:px-2 print:py-2 print:border print:border-black">
                                    <div
                                        class="text-slate-700 truncate max-w-[150px] print:text-black print:whitespace-normal">
                                        {{ $fine->book->title }}</div>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-bold text-slate-900 print:px-2 print:py-2 print:border print:border-black print:text-black">
                                    Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-12 text-center text-slate-400 print:border print:border-black print:text-black">
                                    Tidak ada data denda pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Hide on Print -->
            <div class="px-6 py-4 border-t border-slate-100 print:hidden">
                {{ $fines->withQueryString()->links() }}
            </div>
        </div>

        <!-- Top Violators (1/3) -->
        <div
            class="lg:col-span-1 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden h-fit print:hidden">
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

    <!-- Print Only: Signature Block -->
    <div class="hidden print:flex justify-end mt-8 avoid-break text-black">
        <div class="text-center min-w-[200px]">
            <p class="mb-1 text-sm">Palembang, {{ now()->isoFormat('D MMMM Y') }}</p>
            <p class="font-bold text-sm">Kepala UPT Perpustakaan</p>

            <div class="h-24"></div>

            <p class="font-bold uppercase text-sm">{{ Auth::user()->name ?? 'NAMA KEPALA' }}</p>
            <p class="text-sm">NIP. ...........................</p>
        </div>
    </div>

</x-layouts.admin>
