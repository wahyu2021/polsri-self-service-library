<x-layouts.auth title="Masuk - Polsri Library">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
        <p class="text-slate-500 mt-1.5 text-sm leading-relaxed">
            Masuk untuk mengakses layanan perpustakaan.
        </p>
    </div>

    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4" x-data="{ isLoading: false }" @submit="isLoading = true">
        @csrf

        <x-ui.input label="Email Address" name="email" type="email" placeholder="student@polsri.ac.id"
            :value="old('email')" required autofocus />

        <div class="flex flex-col gap-1">
            <x-ui.input label="Password" name="password" type="password" placeholder="••••••••" required />

            <div class="flex flex-wrap items-center justify-between gap-y-2 mt-0.5">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                    <input id="remember_me" type="checkbox"
                        class="w-4 h-4 rounded border-slate-300 text-polsri-primary focus:ring-polsri-primary/20 transition cursor-pointer"
                        name="remember">
                    <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Ingat
                        saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-polsri-primary hover:text-orange-700 font-semibold transition-colors"
                        href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>
        </div>

        <div class="pt-2">
            <x-ui.button
                class="w-full justify-center py-3 text-sm font-bold shadow-lg shadow-orange-500/20 hover:shadow-orange-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 rounded-xl disabled:opacity-70 disabled:cursor-wait"
                fullWidth
                ::disabled="isLoading">
                
                <span x-show="!isLoading">Masuk</span>
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
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="text-polsri-primary hover:text-orange-700 font-bold hover:underline transition ml-1 inline-block">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>

</x-layouts.auth>
