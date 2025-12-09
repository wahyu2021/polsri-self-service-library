<x-layouts.auth title="Login - Polsri Library">
    
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800">Selamat Datang</h2>
        <p class="text-slate-500 text-sm">Please sign in to your account</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-success text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <!-- Email Address -->
        <x-ui.input 
            label="Email Address" 
            name="email" 
            type="email" 
            placeholder="student@polsri.ac.id" 
            :value="old('email')" 
            required 
            autofocus 
        />

        <!-- Password -->
        <div class="relative">
            <x-ui.input 
                label="Password" 
                name="password" 
                type="password" 
                placeholder="••••••••" 
                required 
            />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-polsri-primary shadow-sm focus:ring-polsri-primary" name="remember">
                <span class="ml-2 text-sm text-slate-600">Remember me</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-polsri-primary hover:text-orange-600 font-medium" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            @endif
        </div>

        <x-ui.button class="w-full" fullWidth>
            Sign In
        </x-ui.button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        Don't have an account? 
        <a href="{{ route('register') }}" class="text-polsri-primary hover:text-orange-600 font-semibold transition">
            Create Account
        </a>
    </div>

</x-layouts.auth>
