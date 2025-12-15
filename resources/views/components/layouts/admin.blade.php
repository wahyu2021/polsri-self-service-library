<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} - Polsri Library</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen bg-slate-50 font-sans text-slate-900 antialiased flex overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 z-40 lg:hidden"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col flex-shrink-0">

        <!-- Logo Area -->
        <div class="flex items-center gap-3 px-6 h-16 flex-shrink-0 border-b border-white/10 bg-slate-900">
            <div
                class="w-10 h-10 rounded-lg flex items-center justify-center">
                <img src="{{ asset('images/logo-lib.png') }}" alt="Logo Polsri Lib" class="object-cover">
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-tight leading-none">PolsriLib</h1>
                <span class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-polsri-primary text-white shadow-lg shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Perpustakaan</p>
            </div>

            <a href="{{ route('admin.logbooks.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.logbooks.*') ? 'bg-polsri-primary text-white shadow-lg shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.logbooks.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="font-medium">Logbook Pengunjung</span>
            </a>

            <a href="{{ route('admin.books.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.books.*') ? 'bg-polsri-primary text-white shadow-lg shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.books.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="font-medium">Manajemen Buku</span>
            </a>

            <a href="{{ route('admin.loans.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.loans.*') ? 'bg-polsri-primary text-white shadow-lg shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.loans.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span class="font-medium">Sirkulasi</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Laporan & User</p>
            </div>

            <a href="{{ route('admin.reports.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.reports.*') ? 'bg-polsri-primary text-white shadow-lg shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="font-medium">Laporan & Denda</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-polsri-primary text-white shadow-lg shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium">Manajemen User</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sistem</p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'bg-polsri-primary text-white shadow-lg shadow-orange-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-medium">Pengaturan</span>
            </a>

        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top Bar -->
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200 h-16 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30 transition-all duration-300 shadow-sm shadow-slate-200/50">
            <!-- Left: Toggle & Title -->
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 lg:hidden p-2 rounded-xl hover:bg-slate-100 transition active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden md:block">
                    <h2 class="font-bold text-slate-800 text-lg tracking-tight">{{ $title ?? 'Admin Panel' }}</h2>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-3 sm:gap-4">
                
                <!-- Global Date Widget -->
                <div class="hidden md:flex items-center gap-2 text-xs font-medium text-slate-500 bg-slate-100/50 px-3 py-1.5 rounded-lg border border-slate-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>

                <!-- Divider -->
                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 pl-2 pr-1 py-1 rounded-xl hover:bg-slate-50 transition group">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 group-hover:text-polsri-primary transition leading-none">{{ Auth::user()->name }}</p>
                            <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider bg-slate-100 px-1.5 py-0.5 rounded mt-1 inline-block">Administrator</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 border-2 border-white shadow-sm flex items-center justify-center text-slate-600 font-bold overflow-hidden ring-2 ring-transparent group-hover:ring-slate-100 transition-all">
                             {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <!-- Chevron -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 hidden sm:block transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 py-2 z-50 origin-top-right"
                        style="display: none;">
                        
                        <!-- Mobile User Info -->
                        <div class="px-4 py-3 border-b border-slate-50 sm:hidden bg-slate-50/50">
                            <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">Administrator</p>
                        </div>

                        <div class="px-2">
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white group-hover:shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <span class="font-medium">Pengaturan</span>
                            </a>
                        </div>
                        
                        <div class="border-t border-slate-100 my-2 mx-2"></div>
                        
                        <div class="px-2 pb-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-rose-100/50 flex items-center justify-center text-rose-500 group-hover:bg-white group-hover:shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    </div>
                                    <span class="font-bold">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- Page Content Scrollable Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6 lg:p-8">
            {{ $slot }}
        </main>

    </div>

    <!-- SweetAlert Toast Logic -->
    <script>
        // Global Confirmation Dialog
        window.confirmAction = function(event, message, type = 'danger') {
            event.preventDefault();
            const form = event.target;
            
            let confirmBtnClass = 'bg-rose-500 hover:bg-rose-600 text-white rounded-xl px-5 py-2.5 text-sm font-bold shadow-md shadow-rose-200 transition-all transform active:scale-95 mr-2';
            let iconType = 'warning';

            if (type === 'success') {
                confirmBtnClass = 'bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl px-5 py-2.5 text-sm font-bold shadow-md shadow-emerald-200 transition-all transform active:scale-95 mr-2';
                iconType = 'question';
            } else if (type === 'info') {
                 confirmBtnClass = 'bg-blue-500 hover:bg-blue-600 text-white rounded-xl px-5 py-2.5 text-sm font-bold shadow-md shadow-blue-200 transition-all transform active:scale-95 mr-2';
                 iconType = 'info';
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: iconType,
                showCancelButton: true,
                buttonsStyling: false,
                heightAuto: false,
                showLoaderOnConfirm: false, // Disable default loader swapping
                customClass: {
                    container: 'z-[9999]',
                    popup: 'bg-white rounded-2xl shadow-xl border border-slate-100 p-6',
                    title: 'text-slate-800 text-lg font-bold mb-2',
                    htmlContainer: 'text-slate-500 text-sm mb-6',
                    confirmButton: confirmBtnClass,
                    cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl px-5 py-2.5 text-sm font-bold transition-all transform active:scale-95 ml-2'
                },
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    return new Promise((resolve) => {
                         // Manually show loader inside the button to preserve styling
                         const btn = Swal.getConfirmButton();
                         btn.innerHTML = `
                             <div class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses...</span>
                             </div>
                         `;
                         btn.style.opacity = '0.7';
                         btn.style.pointerEvents = 'none'; // Disable clicks
                         
                         form.submit();
                    });
                }
            });

            return false;
        };

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
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

            @if (session('error'))
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
