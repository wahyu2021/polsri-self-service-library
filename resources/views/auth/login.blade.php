<x-layouts.auth title="Masuk - Polsri Library">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
        <p class="text-slate-500 mt-1.5 text-sm leading-relaxed">
            Masuk untuk mengakses layanan perpustakaan.
        </p>
    </div>

    @if (session('status'))
        <div
            class="mb-6 p-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium border border-emerald-100 flex items-start gap-2.5">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
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
                class="w-full justify-center py-3 text-sm font-bold shadow-lg shadow-orange-500/20 hover:shadow-orange-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 rounded-xl"
                fullWidth>
                Masuk
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
