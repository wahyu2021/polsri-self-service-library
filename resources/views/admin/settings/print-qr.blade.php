<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code Absensi - UPT Perpustakaan</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            /* Container A4 */
            .page-container {
                width: 210mm;
                height: 297mm;
                padding: 10mm;
                margin: 0 auto;
                background: white;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
            }
    
            .border-frame {
                flex: 1;
                border: 3px solid black;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
            }
        }
      
        @media screen {
            body { background: #f3f4f6; padding: 20px; display: flex; justify-content: center; }
            .page-container {
                width: 210mm;
                height: 297mm;
                background: white;
                padding: 10mm;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
            }
            .border-frame {
                flex: 1;
                border: 3px solid black;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        
        <div class="border-frame p-8 text-center">
            
            <div class="border-b-4 border-black pb-4 mb-8 flex items-center justify-center gap-4">
                <img src="{{ asset('images/logo-polsri.png') }}" alt="Logo" class="w-20 h-20 object-contain">
                <div class="text-left">
                    <h2 class="text-sm font-bold uppercase tracking-[0.2em] text-gray-600">Smart Logbook</h2>
                    <h1 class="text-3xl font-extrabold uppercase text-black leading-none">UPT PERPUSTAKAAN</h1>
                    <p class="text-xs font-bold text-gray-500 mt-1">POLITEKNIK NEGERI SRIWIJAYA</p>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center">
                
                <div class="p-3 border-2 border-dashed border-gray-300 rounded-xl bg-white mb-2">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" class="w-[300px] h-[300px]">
                </div>
                <p class="font-mono text-[10px] text-gray-400 mb-6">ID: SECURE-ENCRYPTED</p>

                <h3 class="text-xl font-bold text-black uppercase mb-3">Scan Untuk Masuk</h3>
                
                <div class="bg-black text-white py-2 px-8 rounded-full font-bold text-sm shadow-lg mb-4">
                    Buka Menu "Logbook" di Aplikasi
                </div>

                <p class="text-gray-600 text-sm mx-auto leading-relaxed px-4">
                    Silakan scan QR Code ini menggunakan menu Logbook pada aplikasi<br> 
                    <b>Self Service Perpustakaan</b> untuk mencatat kehadiran kunjungan Anda.
                </p>
            </div>

            <div class="mt-8 pt-4 border-t-4 border-black w-full">
                <div class="text-left text-[10px] font-mono text-gray-500 leading-tight">
                    Generated: {{ now()->format('d M Y H:i') }}<br>
                    System: Polsri Self-Service Library
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold uppercase mb-12">Tertanda,</p>
                    <p class="font-bold text-base border-b border-black inline-block pb-1">Kepala UPT Perpustakaan</p>
                </div>
            </div>

        </div>
    </div>

    <div class="fixed bottom-8 right-8 flex gap-2 no-print">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak PDF
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-full shadow-lg transition">
            Tutup
        </button>
    </div>

</body>
</html>