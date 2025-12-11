<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Polsri Library' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans text-slate-900 antialiased selection:bg-polsri-primary selection:text-white">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">

        <div class="hidden lg:flex w-1/2 relative flex-col justify-between p-12 xl:p-16 overflow-hidden">
            
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=2070&auto=format&fit=crop" 
                     alt="Library Background" 
                     class="w-full h-full object-cover">
                <!-- Gradient Overlay for Readability -->
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/80 to-slate-900/60 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-900/40 to-slate-900/90"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 border border-white/10 flex items-center justify-center backdrop-blur-md shadow-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6 text-polsri-primary drop-shadow-[0_0_8px_rgba(249,115,22,0.5)]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl tracking-tight text-white leading-tight">
                            PolsriLib
                        </h1>
                        <span class="text-xs text-orange-200/60 font-medium tracking-wider uppercase">Self Service System</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 my-auto">
                <h2 class="text-4xl xl:text-5xl font-bold tracking-tight text-white mb-6 leading-[1.15]">
                    Akses Perpustakaan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-200 to-orange-400">Lebih Cepat & Aman.</span>
                </h2>

                <p class="text-lg text-slate-400 leading-relaxed max-w-md font-light">
                    Sistem peminjaman mandiri terintegrasi dengan validasi biometrik dan geolokasi presisi.
                </p>

                <div class="relative z-10 grid grid-cols-1 gap-4 mt-10">

                    <!-- Feature Card 1 -->
                    <div
                        class="group p-4 rounded-2xl bg-white/[0.03] border border-white/10 hover:bg-white/[0.08] hover:border-white/20 transition-all duration-300 backdrop-blur-sm">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center text-orange-400 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white text-sm font-semibold tracking-wide">Presensi Geolokasi</h3>
                                <p class="text-slate-400 text-xs mt-0.5 group-hover:text-slate-300 transition-colors">Validasi lokasi fisik secara real-time</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Card 2 -->
                    <div
                        class="group p-4 rounded-2xl bg-white/[0.03] border border-white/10 hover:bg-white/[0.08] hover:border-white/20 transition-all duration-300 backdrop-blur-sm">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-300 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white text-sm font-semibold tracking-wide">Verifikasi Otomatis</h3>
                                <p class="text-slate-400 text-xs mt-0.5 group-hover:text-slate-300 transition-colors">Proses peminjaman tanpa antri</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- Footer decoration -->
            <div class="relative z-10 flex gap-2">
                 <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                 <div class="w-2 h-2 rounded-full bg-slate-600"></div>
                 <div class="w-2 h-2 rounded-full bg-slate-700"></div>
            </div>

        </div>

        <div class="w-full lg:w-1/2 flex flex-col h-full min-h-screen bg-slate-50/50 relative">

            <div
                class="lg:hidden w-full p-6 flex items-center justify-between border-b border-slate-100 bg-white/80 backdrop-blur-md sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-polsri-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-slate-900 tracking-tight">PolsriLib</span>
                </div>
            </div>

            <div class="flex-1 flex items-center justify-center p-6 sm:p-12 lg:p-20">
                <div class="w-full max-w-[480px] mx-auto bg-white p-8 sm:p-10 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100/60">
                    {{ $slot }}
                </div>
            </div>

            <div
                class="py-6 text-center lg:text-left px-6 sm:px-12 lg:px-20 border-t border-slate-100 lg:border-none">
                <p class="text-xs text-slate-400 font-medium text-center">
                    &copy; {{ date('Y') }} Politeknik Negeri Sriwijaya. All rights reserved.
                </p>
            </div>
        </div>

    </div>

</body>

</html>
