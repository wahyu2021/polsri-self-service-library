<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F97316">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/2232/2232688.png">
    <title>{{ $title ?? 'Polsri Library' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased h-full selection:bg-orange-100 selection:text-orange-900">
    
    <div class="min-h-full flex flex-col">
        
        <!-- ================= DESKTOP NAVIGATION ================= -->
        @if(Auth::check() && Auth::user()->role->value === 'mahasiswa')
        <header class="hidden lg:block bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-polsri-primary rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-orange-500/20">
                        P
                    </div>
                    <span class="font-bold text-xl text-slate-800 tracking-tight">Polsri Library</span>
                </div>

                <!-- Desktop Menu -->
                <nav class="flex items-center gap-1 bg-slate-100/50 p-1.5 rounded-2xl">
                    <a href="{{ route('student.dashboard') }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('student.dashboard') ? 'bg-white text-polsri-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                       Beranda
                    </a>
                    <a href="{{ route('student.logbook.index') }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('student.logbook.*') ? 'bg-white text-polsri-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                       Absensi
                    </a>
                    <a href="{{ route('student.borrow.index') }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('student.borrow.*') ? 'bg-white text-polsri-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                       Pinjam
                    </a>
                    <a href="{{ route('student.collection.index') }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('student.collection.*') ? 'bg-white text-polsri-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                       Koleksi
                    </a>
                </nav>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <a href="{{ route('student.notifications.index') }}" class="relative w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 transition text-slate-500 hover:text-polsri-primary">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        
                        <!-- Dot Indicator -->
                        @if(isset($globalNotificationCount) && $globalNotificationCount > 0)
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                        @endif
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('student.profile.index') }}" class="flex items-center gap-3 pl-4 border-l border-slate-200 group">
                        <div class="text-right hidden xl:block">
                            <p class="text-sm font-bold text-slate-800 group-hover:text-polsri-primary transition">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ Auth::user()->nim }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white shadow-sm overflow-hidden">
                            <img src="{{ Auth::user()->avatar ? \Illuminate\Support\Facades\Storage::url(Auth::user()->avatar) : asset('images/default-profile.jpg') }} "
                                 class="w-full h-full object-cover">
                        </div>
                    </a>
                </div>
            </div>
        </header>
        @endif

        <!-- ================= MAIN CONTENT ================= -->
        <main class="flex-1 pb-[6.5rem] lg:pb-12">
            {{ $slot }}
        </main>
        
        <!-- ================= MOBILE NAVIGATION ================= -->
        @if(Auth::check() && Auth::user()->role->value === 'mahasiswa')
            <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden pointer-events-none">
                <!-- Gradient Fade -->
                <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
                
                <!-- Floating Bar -->
                <nav class="pointer-events-auto relative mx-4 mb-6 bg-white/90 backdrop-blur-xl border border-white/50 shadow-[0_8px_32px_rgba(0,0,0,0.1)] rounded-2xl">
                    <div class="flex justify-around items-center h-16 px-1">
                        
                        <!-- Dashboard -->
                        <a href="{{ route('student.dashboard') }}" class="flex-1 flex flex-col items-center justify-center h-full group">
                            <div class="p-2 rounded-xl transition-all {{ request()->routeIs('student.dashboard') ? 'bg-orange-50 text-polsri-primary -translate-y-1' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-6 h-6" fill="{{ request()->routeIs('student.dashboard') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('student.dashboard') ? '0' : '2' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                        </a>

                        <!-- Notifications (Mobile) -->
                        <a href="{{ route('student.notifications.index') }}" class="flex-1 flex flex-col items-center justify-center h-full group relative">
                            <div class="p-2 rounded-xl transition-all {{ request()->routeIs('student.notifications.*') ? 'bg-orange-50 text-polsri-primary -translate-y-1' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(isset($globalNotificationCount) && $globalNotificationCount > 0)
                                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                                @endif
                            </div>
                        </a>
                        
                        <!-- Center Action (Pinjam) -->
                        <div class="relative -top-6">
                            <a href="{{ route('student.borrow.index') }}" class="flex items-center justify-center w-14 h-14 rounded-full bg-polsri-primary text-white shadow-lg shadow-orange-500/30 ring-4 ring-white active:scale-95 transition-transform">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        </div>

                        <!-- Collection -->
                        <a href="{{ route('student.collection.index') }}" class="flex-1 flex flex-col items-center justify-center h-full group">
                            <div class="p-2 rounded-xl transition-all {{ request()->routeIs('student.collection.*') ? 'bg-orange-50 text-polsri-primary -translate-y-1' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-6 h-6" fill="{{ request()->routeIs('student.collection.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('student.collection.*') ? '0' : '2' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </a>

                        <!-- Profile -->
                        <a href="{{ route('student.profile.index') }}" class="flex-1 flex flex-col items-center justify-center h-full group">
                            <div class="p-2 rounded-xl transition-all {{ request()->routeIs('student.profile.*') ? 'bg-orange-50 text-polsri-primary -translate-y-1' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-6 h-6" fill="{{ request()->routeIs('student.profile.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('student.profile.*') ? '0' : '2' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </a>

                    </div>
                </nav>
            </div>
        @endif

    </div>

    <!-- SweetAlert Toast Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                window.Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}",
                    customClass: {
                        popup: 'shadow-lg shadow-emerald-500/20 rounded-2xl border-l-[6px] border-emerald-500 bg-white/95 backdrop-blur-xl pl-4 pr-6 py-4 !max-w-[90vw] sm:!max-w-sm',
                        title: 'font-bold text-slate-800 text-sm',
                        timerProgressBar: '!bg-emerald-500'
                    },
                    iconColor: '#10b981'
                });
            @endif

            @if(session('error'))
                window.Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}",
                    customClass: {
                        popup: 'shadow-lg shadow-rose-500/20 rounded-2xl border-l-[6px] border-rose-500 bg-white/95 backdrop-blur-xl pl-4 pr-6 py-4 !max-w-[90vw] sm:!max-w-sm',
                        title: 'font-bold text-slate-800 text-sm',
                        timerProgressBar: '!bg-rose-500'
                    },
                    iconColor: '#f43f5e'
                });
            @endif
        });
    </script>
</body>
</html>