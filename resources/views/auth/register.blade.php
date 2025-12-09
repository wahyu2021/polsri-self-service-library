<x-layouts.auth title="Register - Polsri Library">
    
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800">Create Account</h2>
        <p class="text-slate-500 text-sm">Join the digital library community</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <!-- Name -->
        <x-ui.input 
            label="Full Name" 
            name="name" 
            type="text" 
            placeholder="John Doe" 
            :value="old('name')" 
            required 
            autofocus 
        />

        <!-- NIM -->
        <x-ui.input 
            label="NIM (Student ID)" 
            name="nim" 
            type="text" 
            placeholder="061xxxxxx" 
            :value="old('nim')" 
            required 
        />

        <!-- Email Address -->
        <x-ui.input 
            label="Email Address" 
            name="email" 
            type="email" 
            placeholder="student@polsri.ac.id" 
            :value="old('email')" 
            required 
        />

        <!-- Password -->
        <x-ui.input 
            label="Password" 
            name="password" 
            type="password" 
            placeholder="Min. 8 characters" 
            required 
        />

        <!-- Confirm Password -->
        <x-ui.input 
            label="Confirm Password" 
            name="password_confirmation" 
            type="password" 
            placeholder="Repeat password" 
            required 
        />

        <x-ui.button class="w-full mt-2" fullWidth>
            Register
        </x-ui.button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-polsri-primary hover:text-orange-600 font-semibold transition">
            Sign In
        </a>
    </div>

</x-layouts.auth>
