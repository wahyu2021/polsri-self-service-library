<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code Absensi - UPT Perpustakaan Polsri</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen py-10">

    <!-- Card -->
    <div class="bg-white p-12 border-4 border-black max-w-[210mm] w-full shadow-2xl relative text-center">
        
        <!-- Header -->
        <div class="border-b-4 border-black pb-6 mb-8 flex items-center justify-center gap-6">
            <img src="{{ asset('images/logo-polsri.png') }}" alt="Logo Polsri" class="w-24 h-24 object-contain">
            <div class="text-left">
                <h2 class="text-xl font-bold uppercase tracking-widest text-gray-600">Smart Logbook</h2>
                <h1 class="text-4xl font-extrabold uppercase tracking-tight text-black mt-1">UPT PERPUSTAKAAN</h1>
                <p class="text-sm font-semibold tracking-wide mt-1 text-gray-500">POLITEKNIK NEGERI SRIWIJAYA</p>
            </div>
        </div>

        <!-- QR Area -->
        <div class="my-10 flex flex-col items-center justify-center">
            <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl mb-4">
                <div id="qrcode" class="p-2"></div>
            </div>
            <p class="font-mono text-xs text-gray-400 mt-2">ID: SECURE-ENCRYPTED</p>
        </div>

        <!-- Instructions -->
        <div class="space-y-4 max-w-lg mx-auto">
            <h3 class="text-2xl font-bold text-black uppercase">Scan Untuk Masuk</h3>
            <div class="bg-black text-white py-3 px-6 rounded-full font-bold text-lg inline-block">
                Buka Menu "Logbook" di Aplikasi
            </div>
            <p class="text-gray-600 text-sm mt-4 px-8 leading-relaxed">
                Silakan scan QR Code ini menggunakan menu Logbook pada aplikasi Self Service Perpustakaan untuk mencatat kehadiran kunjungan Anda.
            </p>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t-4 border-black flex justify-between items-end">
            <div class="text-left text-xs font-mono text-gray-500">
                Generated: {{ now()->format('d M Y H:i') }}<br>
                System: Polsri Self-Service Library
            </div>
            <div class="text-right">
                <p class="text-sm font-bold uppercase">Tertanda,</p>
                <p class="mt-8 font-bold text-lg underline">Kepala UPT Perpustakaan</p>
            </div>
        </div>

    </div>

    <!-- Controls -->
    <div class="mt-8 flex gap-4 no-print">
        <button onclick="window.print()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg transition transform hover:-translate-y-1">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg shadow-lg transition transform hover:-translate-y-1">
            Tutup
        </button>
    </div>

    <script type="text/javascript">
        // Generate QR Code
        var qrContent = "{{ $qrContent }}";
        
        new QRCode(document.getElementById("qrcode"), {
            text: qrContent,
            width: 300,
            height: 300,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>