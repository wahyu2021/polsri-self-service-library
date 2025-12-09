<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Polsri Library' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center font-sans text-slate-900 selection:bg-polsri-primary selection:text-white">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
        
        <!-- Header / Logo Area -->
        <div class="bg-polsri-secondary p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <h1 class="relative z-10 text-3xl font-bold text-white tracking-tight">Polsri Library</h1>
            <p class="relative z-10 text-slate-300 text-sm mt-1">Self-Service Digital Platform</p>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            {{ $slot }}
        </div>
        
    </div>

    <!-- Simple Footer -->
    <div class="fixed bottom-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Politeknik Negeri Sriwijaya. All rights reserved.
    </div>

</body>
</html>
