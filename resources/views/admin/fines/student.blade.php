<x-layouts.admin title="Riwayat Denda Mahasiswa">

    <x-ui.header 
        title="Riwayat Denda Mahasiswa" 
        subtitle="{{ $student->name }} ({{ $student->nim }})"
        :breadcrumbs="[
            ['label' => 'User', 'href' => route('admin.users.index')],
            ['label' => 'Denda']
        ]"
    />

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Unpaid Fines -->
        <x-ui.card class="p-6">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Belum Lunas</p>
                <p class="text-2xl font-bold text-rose-600">Rp {{ number_format($fineStatus['total_unpaid'], 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ count($fineStatus['unpaid']) }} transaksi</p>
            </div>
        </x-ui.card>

        <!-- Paid Fines -->
        <x-ui.card class="p-6">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Sudah Lunas</p>
                <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($fineStatus['paid']->sum('fine_amount'), 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ count($fineStatus['paid']) }} transaksi</p>
            </div>
        </x-ui.card>

        <!-- Total Fine History -->
        <x-ui.card class="p-6">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-1">Total Keseluruhan</p>
                <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($fineStatus['total_unpaid'] + $fineStatus['paid']->sum('fine_amount'), 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-2">{{ count($fineStatus['unpaid']) + count($fineStatus['paid']) }} transaksi</p>
            </div>
        </x-ui.card>
    </div>

    <!-- Unpaid Fines Table -->
    @if(count($fineStatus['unpaid']) > 0)
    <x-ui.card class="p-6 mb-8">
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
            <span class="w-1 h-1 rounded-full bg-rose-600"></span>
            Denda Belum Lunas ({{ count($fineStatus['unpaid']) }})
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Transaksi</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-right">Denda</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($fineStatus['unpaid'] as $loan)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-slate-700">{{ $loan->transaction_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 max-w-[250px] truncate" title="{{ $loan->book->title }}">
                                    {{ $loan->book->title }}
                                </div>
                                <div class="text-xs text-slate-500 font-mono">{{ $loan->book->isbn }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-600 text-sm">
                                    {{ $loan->return_date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-mono font-bold text-rose-600">
                                    Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.loans.edit', $loan) }}"
                                    class="px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>
    @endif

    <!-- Paid Fines Table -->
    @if(count($fineStatus['paid']) > 0)
    <x-ui.card class="p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
            <span class="w-1 h-1 rounded-full bg-emerald-600"></span>
            Denda Sudah Lunas ({{ count($fineStatus['paid']) }})
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Transaksi</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-right">Denda</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($fineStatus['paid'] as $loan)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-slate-700">{{ $loan->transaction_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 max-w-[250px] truncate" title="{{ $loan->book->title }}">
                                    {{ $loan->book->title }}
                                </div>
                                <div class="text-xs text-slate-500 font-mono">{{ $loan->book->isbn }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-600 text-sm">
                                    {{ $loan->return_date->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-mono font-bold text-emerald-600">
                                    Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.loans.edit', $loan) }}"
                                    class="px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>
    @endif

    <!-- Empty State -->
    @if(count($fineStatus['unpaid']) === 0 && count($fineStatus['paid']) === 0)
    <x-ui.card class="p-12 text-center">
        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-slate-600 font-bold text-lg">Tidak ada riwayat denda</p>
        <p class="text-slate-500 text-sm mt-1">Mahasiswa ini tidak memiliki denda</p>
    </x-ui.card>
    @endif

</x-layouts.admin>
