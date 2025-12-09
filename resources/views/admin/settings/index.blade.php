<x-layouts.admin title="Pengaturan Sistem">

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <x-ui.header title="Pengaturan Sistem" subtitle="Konfigurasi parameter global aplikasi." />

        @if(session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Lokasi Card -->
            <x-ui.card class="mb-6">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900">Validasi Lokasi (Geofencing)</h3>
                    <p class="text-xs text-slate-500 mt-1">Tentukan titik pusat perpustakaan dan radius toleransi absensi.</p>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Map Container -->
                    <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm relative">
                        <div id="map" class="h-[400px] w-full z-0"></div>
                        
                        <!-- Map Legend/Info -->
                        <div class="absolute top-4 right-4 z-[400] bg-white/90 backdrop-blur-sm p-3 rounded-lg shadow-md border border-slate-100 text-xs max-w-[200px]">
                            <p class="font-semibold text-slate-800 mb-1">Panduan:</p>
                            <ul class="list-disc list-inside text-slate-600 space-y-1">
                                <li>Geser <strong>Marker Biru</strong> untuk menentukan titik pusat.</li>
                                <li>Klik di peta untuk memindahkan pin.</li>
                                <li>Lingkaran biru adalah area radius.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Inputs (Readonly recommended but editable if needed) -->
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

            <!-- Keuangan Card -->
            <x-ui.card class="mb-8">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900">Keuangan & Denda</h3>
                </div>
                <div class="p-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tarif Denda per Hari</label>
                        <div class="relative max-w-xs">
                            <span class="absolute left-4 top-2.5 text-slate-500 text-sm font-bold">Rp</span>
                            <input type="number" name="fine_per_day" value="{{ $settings['fine_per_day']->value ?? '1000' }}" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary transition font-bold text-slate-900" required>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-polsri-primary hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all hover:-translate-y-0.5">
                    Simpan Konfigurasi
                </button>
            </div>

        </form>
    </div>

    <!-- Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Get Initial Values
            var lat = parseFloat(document.getElementById('lat_input').value) || -2.986383; // Default Polsri
            var lng = parseFloat(document.getElementById('lng_input').value) || 104.730248;
            var radius = parseInt(document.getElementById('radius_input').value) || 50;

            // 2. Initialize Map
            var map = L.map('map').setView([lat, lng], 17);

            // 3. Add Tile Layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // 4. Add Marker & Circle
            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            var circle = L.circle([lat, lng], {
                color: '#F97316', // Polsri Primary Color
                fillColor: '#F97316',
                fillOpacity: 0.2,
                radius: radius
            }).addTo(map);

            // 5. Function to update inputs
            function updatePosition(latlng) {
                document.getElementById('lat_input').value = latlng.lat.toFixed(7);
                document.getElementById('lng_input').value = latlng.lng.toFixed(7);
                
                marker.setLatLng(latlng);
                circle.setLatLng(latlng);
                map.panTo(latlng);
            }

            // 6. Event Listeners
            
            // Drag Marker
            marker.on('dragend', function(e) {
                updatePosition(marker.getLatLng());
            });

            // Click Map
            map.on('click', function(e) {
                updatePosition(e.latlng);
            });

            // Update Circle Radius manually
            window.updateCircle = function() {
                var newRadius = parseInt(document.getElementById('radius_input').value) || 0;
                circle.setRadius(newRadius);
            }
        });
    </script>

</x-layouts.admin>
