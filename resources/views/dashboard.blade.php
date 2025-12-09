<x-layouts.app>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-polsri-secondary mb-6">Welcome to Your Dashboard!</h1>
        <p class="text-slate-700">You are logged in.</p>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <x-ui.button type="submit" variant="secondary">Log Out</x-ui.button>
        </form>
    </div>
</x-layouts.app>
