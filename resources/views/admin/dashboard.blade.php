<x-layouts.admin title="Dashboard">
    
    <!-- Top Header & Actions -->
    <x-ui.header 
        title="Dashboard" 
        subtitle="Overview aktivitas perpustakaan hari ini."
    >
        <div class="flex items-center gap-3">
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
    </x-ui.header>

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
        
        <!-- Main Content (Left) -->
        <div class="xl:col-span-8 flex flex-col gap-8">
            
            <!-- 1. Validation Queue -->
            <div id="validation-queue" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden scroll-mt-24">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white border border-slate-200 rounded-lg shadow-sm">
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

            <!-- 2. Recent Transactions (New) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white border border-slate-200 rounded-lg shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Sirkulasi Terkini</h3>
                            <p class="text-slate-500 text-xs">Aktivitas peminjaman dan pengembalian terbaru.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.loans.index') }}" class="text-xs font-bold text-polsri-primary hover:text-orange-700 transition">LIHAT SEMUA</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white text-slate-500 border-b border-slate-100 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-semibold">Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold">Buku</th>
                                <th class="px-6 py-4 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentTransactions as $transaction)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $transaction->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 text-xs">{{ $transaction->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $transaction->user->nim }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-slate-700 truncate max-w-[200px]" title="{{ $transaction->book->title }}">
                                            {{ $transaction->book->title }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($transaction->status == \App\Enums\LoanStatus::BORROWED)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                DIPINJAM
                                            </span>
                                        @elseif($transaction->status == \App\Enums\LoanStatus::RETURNED)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                DIKEMBALIKAN
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                {{ $transaction->status->value }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-xs">
                                        Belum ada transaksi sirkulasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Sidebar (Right) -->
        <div class="xl:col-span-4 space-y-6">
            
            <!-- Weekly Visitors Chart -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800 text-sm">Tren Kunjungan</h3>
                    <span class="text-xs text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">7 Hari Terakhir</span>
                </div>
                <div id="visitorsChart" class="-ml-2"></div>
            </div>

            <!-- Popular Books (New) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Buku Populer</h3>
                    <span class="text-[10px] text-slate-400 font-medium bg-white px-2 py-0.5 rounded border border-slate-100">Top 5</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($popularBooks as $index => $book)
                        <div class="flex items-center gap-3 p-4 hover:bg-slate-50/50 transition">
                            <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 font-bold text-xs border border-slate-200">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate" title="{{ $book->title }}">{{ $book->title }}</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ $book->author }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-polsri-primary">
                                    {{ $book->loans_count }}
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs">
                            Belum ada data peminjaman.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Logbook Terbaru</h3>
                    <a href="{{ route('admin.logbooks.index') }}" class="text-[10px] font-bold text-polsri-primary hover:underline">LIHAT SEMUA</a>
                </div>

                <div class="p-0">
                    @forelse($recentLogbooks as $log)
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
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Chart Initialization
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            
            const options = {
                series: [{
                    name: 'Pengunjung',
                    data: chartData.map(item => item.count)
                }],
                chart: {
                    type: 'area',
                    height: 200,
                    toolbar: { show: false },
                    fontFamily: 'Instrument Sans, sans-serif',
                    zoom: { enabled: false }
                },
                colors: ['#f97316'], // Orange-500
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: chartData.map(item => item.date),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '10px' }
                    }
                },
                yaxis: {
                    show: false
                },
                grid: {
                    show: true,
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    padding: { left: 0, right: 0, top: 0, bottom: 0 }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " Mahasiswa"
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#visitorsChart"), options);
            chart.render();
        });

        function handleApprove(event, form) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Setujui Peminjaman?',
                text: "Mahasiswa akan diizinkan membawa buku keluar.",
                icon: 'question',
                showCancelButton: true,
                buttonsStyling: false,
                heightAuto: false, // Prevent layout shifts
                customClass: {
                    container: 'z-[9999]', // Force very high z-index
                    popup: 'bg-white rounded-2xl shadow-xl border border-slate-100 p-6',
                    title: 'text-slate-800 text-lg font-bold mb-2',
                    htmlContainer: 'text-slate-500 text-sm mb-6',
                    confirmButton: 'bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl px-5 py-2.5 text-sm font-bold shadow-md shadow-emerald-200 transition-all transform active:scale-95 mr-2',
                    cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl px-5 py-2.5 text-sm font-bold transition-all transform active:scale-95 ml-2'
                },
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    processApprove(form);
                }
            });

            return false;
        }

        function processApprove(form) {
            const btn = form.querySelector('button');
            const originalContent = btn.innerHTML;
            
            // Loading state
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                WAIT...
            `;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Show Toast
                    window.Toast.fire({
                        icon: 'success',
                        title: data.message,
                        customClass: {
                            popup: 'shadow-lg shadow-emerald-500/20 rounded-2xl border-l-[6px] border-emerald-500 bg-white/95 backdrop-blur-xl pl-4 pr-6 py-4 !max-w-[90vw] sm:!max-w-sm',
                            title: 'font-bold text-slate-800 text-sm',
                            timerProgressBar: '!bg-emerald-500'
                        },
                        iconColor: '#10b981'
                    });

                    // Remove row with animation
                    const row = form.closest('tr');
                    row.classList.add('transition-opacity', 'duration-500', 'opacity-0');
                    setTimeout(() => row.remove(), 500);

                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.Toast.fire({
                    icon: 'error',
                    title: error.message || 'Terjadi kesalahan saat memproses.',
                    customClass: {
                        popup: 'shadow-lg shadow-rose-500/20 rounded-2xl border-l-[6px] border-rose-500 bg-white/95 backdrop-blur-xl pl-4 pr-6 py-4 !max-w-[90vw] sm:!max-w-sm',
                        title: 'font-bold text-slate-800 text-sm',
                        timerProgressBar: '!bg-rose-500'
                    },
                    iconColor: '#f43f5e'
                });
                
                // Reset button
                btn.disabled = false;
                btn.innerHTML = originalContent;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Poll for new validation requests every 5 seconds
            setInterval(function() {
                fetch("{{ route('admin.dashboard.validation-queue') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
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