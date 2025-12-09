<x-layouts.app title="Smart Logbook">
    <x-slot name="header">
        Smart Logbook
    </x-slot>

    <div class="max-w-md mx-auto min-h-screen flex flex-col bg-black">
        
        <!-- Camera Viewfinder UI -->
        <div class="relative flex-1 bg-gray-900 flex items-center justify-center overflow-hidden">
            
            <!-- Simulasi Kamera (Placeholder Video) -->
            <div class="absolute inset-0 opacity-40">
                <div class="w-full h-full bg-[url('https://images.unsplash.com/photo-1555421689-d68471e189f2?auto=format&fit=crop&q=80')] bg-cover bg-center filter blur-sm"></div>
            </div>

            <!-- Overlay Interface -->
            <div class="absolute inset-0 z-10 flex flex-col items-center pt-20 pb-32 px-6">
                
                <!-- GPS Status Indicator -->
                <div id="gps-indicator" class="bg-black/50 backdrop-blur-md text-white/80 px-4 py-2 rounded-full flex items-center gap-2 mb-8 border border-white/10 transition-all duration-300">
                    <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse" id="gps-dot"></span>
                    <span class="text-xs font-medium tracking-wide" id="gps-text">Mencari Lokasi...</span>
                </div>

                <!-- Scanner Frame -->
                <div class="relative w-64 h-64 border-2 border-white/30 rounded-3xl">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-500 rounded-tl-2xl -mt-1 -ml-1"></div>
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-500 rounded-tr-2xl -mt-1 -mr-1"></div>
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-500 rounded-bl-2xl -mb-1 -ml-1"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-500 rounded-br-2xl -mb-1 -mr-1"></div>
                    
                    <!-- Scanning Line Animation -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-blue-500/50 shadow-[0_0_10px_rgba(59,130,246,0.5)] animate-[scan_2s_ease-in-out_infinite]"></div>
                </div>

                <p class="text-white/60 text-sm mt-8 text-center max-w-xs">
                    Arahkan kamera ke QR Code di pintu masuk perpustakaan.
                </p>

            </div>

            <!-- Controls (Bottom Sheet) -->
            <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[2rem] p-8 z-20">
                <div class="flex flex-col gap-4">
                    
                    <!-- Form Hidden -->
                    <form id="checkin-form" class="hidden">
                        @csrf
                        <input type="hidden" name="qr_code" id="input_qr_code">
                        <input type="hidden" name="latitude" id="input_latitude">
                        <input type="hidden" name="longitude" id="input_longitude">
                    </form>

                    <!-- Status Message -->
                    <div id="status-display" class="hidden p-4 rounded-xl text-center mb-2"></div>

                    <!-- Simulation Buttons (Development Only) -->
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="simulateScan()" class="bg-slate-900 text-white py-3 rounded-xl font-bold active:scale-95 transition">
                            📸 Simulasi Scan
                        </button>
                        <button type="button" onclick="getCurrentLocation()" class="bg-slate-100 text-slate-700 py-3 rounded-xl font-bold active:scale-95 transition">
                            📍 Set Lokasi Saya
                        </button>
                    </div>

                    <a href="{{ route('student.dashboard') }}" class="text-center text-slate-400 text-sm font-medium mt-2">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes scan {
            0%, 100% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
    </style>

    <script>
        const gpsDot = document.getElementById('gps-dot');
        const gpsText = document.getElementById('gps-text');
        const statusDisplay = document.getElementById('status-display');
        let currentLat = null;
        let currentLng = null;

        // Auto start GPS
        document.addEventListener('DOMContentLoaded', () => {
            getCurrentLocation();
        });

        function getCurrentLocation() {
            gpsText.innerText = "Mencari satelit...";
            gpsDot.className = "w-2 h-2 rounded-full bg-yellow-400 animate-pulse";

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        currentLat = position.coords.latitude;
                        currentLng = position.coords.longitude;
                        
                        gpsText.innerText = "Lokasi Akurat (±" + Math.round(position.coords.accuracy) + "m)";
                        gpsDot.className = "w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]";

                        // Update hidden inputs
                        document.getElementById('input_latitude').value = currentLat;
                        document.getElementById('input_longitude').value = currentLng;
                    },
                    (error) => {
                        gpsText.innerText = "Gagal mengambil lokasi";
                        gpsDot.className = "w-2 h-2 rounded-full bg-rose-500";
                    }
                );
            }
        }

        async function simulateScan() {
            if (!currentLat) {
                alert("Tunggu lokasi terkunci dulu!");
                return;
            }

            // Simulasi QR Value
            document.getElementById('input_qr_code').value = 'LIB-POLSRI-ACCESS';
            
            // Submit
            const form = document.getElementById('checkin-form');
            const formData = new FormData(form);

            statusDisplay.classList.remove('hidden');
            statusDisplay.className = "p-4 rounded-xl text-center mb-2 bg-slate-100 text-slate-500";
            statusDisplay.innerHTML = "Memproses data...";

            try {
                const response = await fetch("{{ route('student.logbook.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    statusDisplay.className = "p-4 rounded-xl text-center mb-2 bg-emerald-100 text-emerald-700 font-bold border border-emerald-200";
                    statusDisplay.innerHTML = "✅ " + result.message;
                } else {
                    statusDisplay.className = "p-4 rounded-xl text-center mb-2 bg-rose-100 text-rose-700 font-bold border border-rose-200";
                    statusDisplay.innerHTML = "❌ " + result.message;
                }
            } catch (error) {
                statusDisplay.innerText = "Error Sistem";
            }
        }
    </script>
</x-layouts.app>
