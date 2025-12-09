<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Polsri Library' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans text-slate-900 antialiased">
    <div class="min-h-screen bg-gray-100 {{ (Auth::check() && Auth::user()->role->value === 'mahasiswa') ? 'pb-16' : '' }}">
        
        @if(Auth::check() && Auth::user()->role->value === 'mahasiswa')
            <!-- Student Layout - No top nav, custom header per page -->
            <!-- Page Heading (optional, rendered by specific views) -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endisset
        @else
            <!-- Default Layout (Admin/Guest) - Standard Top Nav -->
            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ Auth::check() ? (Auth::user()->role->value === 'admin' ? route('admin.dashboard') : route('student.dashboard')) : url('/') }}" class="text-2xl font-bold text-polsri-primary">Polsri Library</a>
                            </div>
                        </div>
                        @if(Auth::check())
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-ui.button type="submit" variant="secondary">Log Out</x-ui.button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endisset
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        
        @if(Auth::check() && Auth::user()->role->value === 'mahasiswa')
            <!-- Bottom Navigation for Mahasiswa -->
            <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 shadow-lg">
                <div class="flex justify-around h-16 max-w-md mx-auto">
                    <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 px-3 py-2 text-xs font-medium transition-colors duration-200 {{ request()->routeIs('student.dashboard') ? 'text-blue-600' : '' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('student.logbook.index') }}" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 px-3 py-2 text-xs font-medium transition-colors duration-200 {{ request()->routeIs('student.logbook.*') ? 'text-blue-600' : '' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a2 2 0 00-2-2h-4.586a1 1 0 01-.707-.293l-1.414-1.414a1 1 0 00-.707-.293H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4h.01M16 16h4" /></svg>
                        <span>Absensi</span>
                    </a>
                    <a href="{{ route('student.borrow.index') }}" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 px-3 py-2 text-xs font-medium transition-colors duration-200 {{ request()->routeIs('student.borrow.*') ? 'text-blue-600' : '' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span>Pinjam</span>
                    </a>
                    <a href="{{ route('student.collection.index') }}" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 px-3 py-2 text-xs font-medium transition-colors duration-200 {{ request()->routeIs('student.collection.*') ? 'text-blue-600' : '' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        <span>Koleksi</span>
                    </a>
                </div>
            </nav>
        @endif
    </div>
</body>
</html>
