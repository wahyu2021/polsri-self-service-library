<x-layouts.app title="Smart Logbook">

    <div class="flex flex-col h-[calc(100vh-6.5rem)] lg:h-[calc(100vh-5rem)] bg-black">
        
        <div class="relative flex-1 bg-gray-900 overflow-hidden group">
            
            <div id="reader" class="w-full h-full object-cover"></div>

            <div id="camera-fallback" class="absolute inset-0 flex items-center justify-center text-white/30 text-sm">
                Memuat Kamera...
            </div>

            <div class="absolute inset-0 z-10 flex flex-col items-center justify-center pointer-events-none">
                <div id="gps-indicator" class="absolute top-8 bg-black/40 backdrop-blur-md border border-white/10 px-4 py-2 rounded-full flex items-center gap-2 pointer-events-auto transition-all">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse" id="gps-dot"></span>
                    <span class="text-xs font-medium text-white/90" id="gps-text">Mencari Lokasi...</span>
                </div>

                <div class="w-64 h-64 border-2 border-white/20 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-polsri-primary rounded-tl-xl -mt-1 -ml-1"></div>
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-polsri-primary rounded-tr-xl -mt-1 -mr-1"></div>
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-polsri-primary rounded-bl-xl -mb-1 -ml-1"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-polsri-primary rounded-br-xl -mb-1 -mr-1"></div>
                    <div class="absolute top-0 w-full h-1 bg-gradient-to-r from-transparent via-polsri-primary to-transparent animate-[scan_2s_ease-in-out_infinite] shadow-[0_0_15px_rgba(249,115,22,0.6)]"></div>
                </div>

                <p class="mt-6 text-white/70 text-sm font-medium bg-black/20 px-4 py-1 rounded-lg backdrop-blur-sm">Arahkan ke QR Pintu Masuk</p>
            </div>
        </div>

        <div class="bg-white px-6 py-6 rounded-t-3xl -mt-6 z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.2)]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 text-lg">Scan Masuk</h3>
                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded border border-slate-200">Wajib GPS Aktif</span>
            </div>

            <form id="checkin-form" class="hidden">
                @csrf
                <input type="hidden" name="qr_code" id="input_qr_code">
                <input type="hidden" name="latitude" id="input_latitude">
                <input type="hidden" name="longitude" id="input_longitude">
            </form>

            <div id="status-display" class="hidden mb-4 p-3 rounded-xl text-center text-sm font-bold"></div>

            <div id="feedback-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
                <div class="text-center transform scale-50 transition-transform duration-300" id="feedback-content">
                    <div id="feedback-icon" class="text-9xl mb-4 animate-bounce"></div>
                    <h2 id="feedback-title" class="text-3xl font-bold text-white mb-2"></h2>
                    <p id="feedback-message" class="text-white/80 text-lg"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <button onclick="getCurrentLocation()" class="py-3 px-4 rounded-xl bg-slate-50 text-slate-600 font-bold text-sm hover:bg-slate-100 active:scale-95 transition flex items-center justify-center gap-2">
                    <span id="gps-icon">📍</span> <span id="gps-btn-text">Perbarui Lokasi GPS</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        window.AppConfig = {
            checkInUrl: "{{ route('student.logbook.store') }}",
            dashboardUrl: "{{ route('student.dashboard') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    @vite(['resources/js/student/logbook.js'])
</x-layouts.app>
