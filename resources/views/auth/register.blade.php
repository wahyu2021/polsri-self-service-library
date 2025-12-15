<x-layouts.auth title="Daftar - Polsri Library">

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
            <div class="flex gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-red-900 text-sm">Pendaftaran Gagal</h3>
                    <ul class="mt-2 space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-6 animate-fade-in">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Buat Akun</h2>
        <p class="text-slate-500 mt-1.5 text-sm leading-relaxed">
            Mulai akses layanan mandiri perpustakaan.
        </p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-3" x-data="{ isLoading: false }" @submit="isLoading = true">
        @csrf
        
        <x-ui.input label="Nama Lengkap" name="name" type="text" placeholder="Contoh: Wahyu Saputra"
            :value="old('name')" required autofocus />

        <x-ui.input label="NIM / NIP" name="nim" type="text" placeholder="061xxxxxx" :value="old('nim')"
            required />
        
        <x-ui.input label="Email Institusi" name="email" type="email" placeholder="nim@student.polsri.ac.id"
            :value="old('email')" required />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
            <x-ui.input label="Password" name="password" type="password" placeholder="Min. 8 karakter" required />

            <x-ui.input label="Konfirmasi" name="password_confirmation" type="password"
                placeholder="Ulangi password" required />
        </div>

        <div class="pt-4">
            <x-ui.button
                class="w-full justify-center py-3 text-sm font-bold shadow-lg shadow-orange-500/20 hover:shadow-orange-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 rounded-xl disabled:opacity-70 disabled:cursor-wait"
                fullWidth
                ::disabled="isLoading">
                
                <span x-show="!isLoading">Daftar Sekarang</span>
                <span x-show="isLoading" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </x-ui.button>
        </div>

        <div class="text-center mt-2">
            <p class="text-slate-500 text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="text-polsri-primary hover:text-orange-700 font-bold hover:underline transition ml-1 inline-block">
                    Masuk disini
                </a>
            </p>
            
            <p class="text-xs text-slate-400 mt-6 leading-relaxed max-w-xs mx-auto">
                Dengan mendaftar, anda menyetujui <a href="#" class="underline hover:text-slate-600 transition">Syarat & Ketentuan</a> kami.
            </p>
        </div>
    </form>

</x-layouts.auth>
