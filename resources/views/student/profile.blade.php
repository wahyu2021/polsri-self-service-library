<x-layouts.app title="Profil Saya">
    <x-slot name="header">
        Profil Saya
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    
                    <!-- Profile Picture / Initial -->
                    <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-5xl font-bold mx-auto mb-4 border-4 border-blue-200">
                        {{ substr($user->name, 0, 1) }}
                    </div>

                    <!-- User Info -->
                    <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-slate-500 text-md mt-1">{{ $user->nim }}</p>
                    <p class="text-slate-500 text-md">{{ $user->email }}</p>

                    <!-- ID Card (QR) -->
                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Kartu Perpustakaan Digital</p>
                        
                        <div class="flex justify-center">
                            <div class="p-2 bg-white border-2 border-slate-100 rounded-xl">
                                <!-- QR Code Generator API -->
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $user->nim }}" 
                                     alt="QR Code NIM" 
                                     class="w-40 h-40 rounded-lg">
                            </div>
                        </div>
                        
                        <p class="text-sm text-slate-500 mt-4">Tunjukkan QR Code ini untuk peminjaman di Admin.</p>
                    </div>

                    <!-- Logout Button -->
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-3 text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition">
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
