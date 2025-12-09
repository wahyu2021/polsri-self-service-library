<x-layouts.auth title="Daftar - Polsri Library">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Buat Akun</h2>
        <p class="text-slate-500 mt-1.5 text-sm leading-relaxed">
            Mulai akses layanan mandiri perpustakaan.
        </p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-3">
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
                class="w-full justify-center py-3 text-sm font-bold shadow-lg shadow-orange-500/20 hover:shadow-orange-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 rounded-xl"
                fullWidth>
                Daftar Sekarang
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
