<x-layouts.app title="Profil Saya">
    <x-slot name="header">
        Profil Saya
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    
                    <div class="relative w-24 h-24 mx-auto mb-4 group">
                        <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center border-4 border-orange-200 overflow-hidden shadow-md">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-profile.jpg') }}" 
                                 alt="Profile" 
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <label for="avatar-upload" class="absolute bottom-0 right-0 bg-white text-slate-600 p-1.5 rounded-full shadow-md border border-slate-200 cursor-pointer hover:bg-orange-50 hover:text-polsri-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                    </div>

                    <form id="avatar-form" action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="file" id="avatar-upload" name="avatar" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                    </form>

                    @if($errors->any())
                        <div class="mb-4 p-2 bg-rose-50 text-rose-600 text-xs font-bold rounded-lg border border-rose-200">
                            {{ $errors->first('avatar') }}
                        </div>
                    @endif

                    <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-slate-500 text-md mt-1">{{ $user->nim }}</p>
                    <p class="text-slate-500 text-md">{{ $user->email }}</p>

                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Kartu Perpustakaan Digital</p>
                        
                        <div class="flex justify-center">
                            <div class="p-2 bg-white border-2 border-slate-100 rounded-xl">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $user->nim }}" 
                                     alt="QR Code NIM" 
                                     class="w-40 h-40 rounded-lg">
                            </div>
                        </div>
                        
                        <p class="text-sm text-slate-500 mt-4">Tunjukkan QR Code ini untuk peminjaman di Admin.</p>
                    </div>

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