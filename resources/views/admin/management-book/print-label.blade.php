<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label - {{ $book->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Container Label */
        .label-container {
            width: 8cm; /* Lebar Kartu */
            height: 5cm; /* Tinggi Kartu */
            background: white;
            padding: 0.5cm;
            border-radius: 4px;
            display: flex;
            gap: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            border: 1px dashed #cbd5e1; /* Panduan potong */
        }

        .mono {
            font-family: 'Space Mono', monospace;
        }

        /* Mode Cetak */
        @media print {
            body {
                background: white;
                display: block;
                height: auto;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .label-container {
                box-shadow: none;
                border: 1px solid #ddd; /* Tipis untuk batas */
                margin: 0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Aksi (Hilang saat print) -->
    <div class="fixed top-6 right-6 flex flex-col gap-3 no-print z-50">
        <button onclick="window.print()" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Label
        </button>
        <button onclick="window.close()" class="px-6 py-3 bg-white text-slate-700 font-bold rounded-xl shadow-lg transition flex items-center justify-center gap-2 hover:bg-slate-50">
            Tutup
        </button>
    </div>

    <!-- Area Label -->
    <div class="label-container items-center">
        
        <!-- Kiri: QR Code -->
        <div class="flex-shrink-0 flex flex-col items-center justify-center w-28">
            <!-- QR Code (Generated via API for simplicity in View) -->
            <!-- Using ISBN as content because the scanner looks for ISBN -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $book->isbn }}&margin=0" 
                 alt="QR Code" 
                 class="w-24 h-24 object-contain">
            
            <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Scan Me</span>
        </div>

        <!-- Kanan: Detail Buku -->
        <div class="flex-1 flex flex-col justify-between h-full py-1">
            
            <!-- Header -->
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo-lib.png') }}" alt="Logo" class="w-6 h-6 object-contain">
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-900 leading-none uppercase">Polsri Library</h3>
                        <p class="text-[8px] text-slate-500 leading-none mt-0.5">Self-Service System</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mt-2">
                <h1 class="text-sm font-bold text-slate-900 leading-snug line-clamp-2">
                    {{ $book->title }}
                </h1>
                <p class="text-[10px] text-slate-500 font-medium mt-1 truncate">
                    {{ $book->author }}
                </p>
            </div>

            <!-- Footer: ISBN / Call Number -->
            <div class="mt-auto pt-2 border-t border-slate-100">
                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">ISBN Number</p>
                <p class="text-lg font-bold text-slate-900 mono tracking-tight leading-none">
                    {{ $book->isbn }}
                </p>
            </div>
        </div>

        <!-- Decorative Bar -->
        <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-orange-500"></div>
    </div>

</body>
</html>
