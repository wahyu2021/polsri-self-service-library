<x-layouts.admin title="Pengaturan Sistem">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="max-w-7xl mx-auto">
        <x-ui.header title="Pengaturan Sistem" subtitle="Konfigurasi parameter global aplikasi." />

        <form action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2 space-y-6">
                <x-ui.card>
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-900">Validasi Lokasi (Geofencing)</h3>
                            <p class="text-xs text-slate-500 mt-1">Tentukan titik pusat perpustakaan dan radius toleransi absensi.</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.settings.qr-pdf') }}" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                            <a href="{{ route('admin.settings.print-qr') }}" target="_blank" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 hover:text-polsri-primary hover:border-polsri-primary rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm relative">
                            <div id="map" class="h-[400px] w-full z-0"></div>
                            
                            <div class="absolute top-4 right-4 z-[400] bg-white/90 backdrop-blur-sm p-3 rounded-lg shadow-md border border-slate-100 text-xs max-w-[200px]">
                                <p class="font-semibold text-slate-800 mb-1">Panduan:</p>
                                <ul class="list-disc list-inside text-slate-600 space-y-1">
                                    <li>Geser <strong>Marker Biru</strong> untuk titik pusat.</li>
                                    <li>Klik peta untuk memindahkan pin.</li>
                                    <li>Area oranye adalah radius validasi.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Latitude</label>
                                <input type="text" id="lat_input" name="library_lat" value="{{ $settings['library_lat']->value ?? '-2.986383' }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Longitude</label>
                                <input type="text" id="lng_input" name="library_lng" value="{{ $settings['library_lng']->value ?? '104.730248' }}" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Radius (Meter)</label>
                                <div class="relative">
                                    <input type="number" id="radius_input" name="validation_radius" value="{{ $settings['validation_radius']->value ?? '50' }}" 
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition" oninput="updateCircle()">
                                    <span class="absolute right-4 top-2.5 text-slate-400 text-sm font-medium">m</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <x-ui.card>
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="font-bold text-slate-900">Keuangan & Denda</h3>
                        <p class="text-xs text-slate-500 mt-1">Konfigurasi tarif denda keterlambatan.</p>
                    </div>
                    <div class="p-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tarif Denda per Hari</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-slate-500 text-sm font-bold">Rp</span>
                                <input type="number" name="fine_per_day" value="{{ $settings['fine_per_day']->value ?? '1000' }}" 
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition font-bold text-slate-900" required>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Dihitung otomatis saat buku dikembalikan.</p>
                        </div>
                    </div>
                </x-ui.card>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <button type="submit" class="w-full px-8 py-3 bg-polsri-primary hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Simpan Konfigurasi
                    </button>
                </div>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial Configuration
            var lat = Number("{{ $settings['library_lat']->value ?? '-2.986383' }}");
            var lng = Number("{{ $settings['library_lng']->value ?? '104.7315341' }}");
            var radius = Number("{{ $settings['validation_radius']->value ?? '50' }}");

            // Initialize Map
            var map = L.map('map').setView([lat, lng], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Add Marker & Validation Circle
            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            var circle = L.circle([lat, lng], {
                color: '#F97316', // Polsri Primary Color
                fillColor: '#F97316',
                fillOpacity: 0.2,
                radius: radius
            }).addTo(map);

            // Sync Inputs with Map
            function updatePosition(latlng) {
                document.getElementById('lat_input').value = latlng.lat.toFixed(7);
                document.getElementById('lng_input').value = latlng.lng.toFixed(7);
                
                marker.setLatLng(latlng);
                circle.setLatLng(latlng);
                map.panTo(latlng);
            }

            // Event Listeners
            marker.on('dragend', function(e) {
                updatePosition(marker.getLatLng());
            });

            map.on('click', function(e) {
                updatePosition(e.latlng);
            });

            window.updateCircle = function() {
                var newRadius = parseInt(document.getElementById('radius_input').value) || 0;
                circle.setRadius(newRadius);
            }
        });
    </script>

</x-layouts.admin>