<x-layouts.app title="Tiket Keluar">
    <div class="min-h-screen bg-slate-900 text-white flex flex-col items-center justify-center p-6">
        
        <div class="w-full max-w-sm bg-white text-slate-900 rounded-3xl overflow-hidden shadow-2xl relative">
            
            <div class="bg-blue-600 p-6 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIi8+PC9zdmc+')] opacity-30"></div>
                <h1 class="text-white font-bold text-xl tracking-widest uppercase">Exit Pass</h1>
                <p class="text-blue-100 text-xs mt-1">Self-Service Library Polsri</p>
            </div>

            <div class="p-8 flex flex-col items-center">
                
                <div id="status-badge" class="mb-6 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-yellow-100 text-yellow-700 animate-pulse border border-yellow-200">
                    Menunggu Verifikasi
                </div>

                <div class="p-4 bg-white border-4 border-slate-900 rounded-2xl shadow-sm mb-6">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $loan->transaction_code }}" 
                         alt="QR Code Transaksi" 
                         class="w-48 h-48">
                </div>

                <div class="text-center space-y-1">
                    <p class="text-slate-400 text-xs uppercase tracking-wider">Kode Transaksi</p>
                    <p class="text-2xl font-mono font-bold text-slate-900 tracking-tight">{{ $loan->transaction_code }}</p>
                </div>

                <div class="mt-8 pt-6 border-t border-dashed border-slate-200 w-full">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-500">Judul Buku</span>
                        <span class="font-bold text-slate-900 text-right w-1/2 truncate">{{ $loan->book->title }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tanggal</span>
                        <span class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($loan->created_at)->format('d M Y, H:i') }}</span>
                    </div>
                </div>

            </div>

            <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
                <p id="instruction-text" class="text-xs text-slate-500">
                    Tunjukkan QR Code ini kepada petugas di pintu keluar. <br>
                    <span class="text-rose-500 font-bold">Jangan tutup halaman ini.</span>
                </p>
                <div class="mt-4">
                    <a href="{{ route('student.dashboard') }}" class="text-slate-400 text-xs hover:text-slate-600 underline">Kembali ke Dashboard</a>
                </div>
            </div>

            <div class="absolute top-[88px] -left-3 w-6 h-6 bg-slate-900 rounded-full"></div>
            <div class="absolute top-[88px] -right-3 w-6 h-6 bg-slate-900 rounded-full"></div>

        </div>
    </div>

    <script>
        const loanId = "{{ $loan->id }}";
        const statusBadge = document.getElementById('status-badge');
        const instructionText = document.getElementById('instruction-text');
        
        let checkInterval = setInterval(checkStatus, 3000);

        async function checkStatus() {
            try {
                const response = await fetch("{{ route('student.ticket.status', $loan->id) }}");
                const data = await response.json();

                if (data.status === 'borrowed') {
                    clearInterval(checkInterval);
                    updateUIApproved();
                }
            } catch (e) {
                console.error("Polling error", e);
            }
        }

        function updateUIApproved() {
            statusBadge.className = "mb-6 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200";
            statusBadge.innerText = "Disetujui / Silakan Keluar";
            
            instructionText.innerHTML = "Buku berhasil dipinjam.<br>Terima kasih telah membaca!";
            instructionText.classList.add("text-emerald-600", "font-medium");
            
            if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
        }
    </script>
</x-layouts.app>