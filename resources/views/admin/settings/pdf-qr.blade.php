<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>QR Code Absensi</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            height: 100vh;
            display: table;
            text-align: center;
        }
        .content {
            display: table-cell;
            vertical-align: middle;
            padding: 40px;
        }
        .border-frame {
            border: 4px solid #000;
            padding: 40px;
            margin: 20px;
            height: 90vh;
            box-sizing: border-box;
            position: relative;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        .logo {
            width: 80px;
            height: auto;
            vertical-align: middle;
            margin-right: 20px;
        }
        .header-text {
            display: inline-block;
            vertical-align: middle;
            text-align: left;
        }
        .header-title {
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            line-height: 1;
        }
        .header-subtitle {
            font-size: 12px;
            font-weight: bold;
            color: #666;
            margin: 5px 0 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .qr-box {
            border: 2px dashed #ccc;
            padding: 20px;
            display: inline-block;
            margin-bottom: 20px;
            background: #fff;
            border-radius: 10px;
        }
        .qr-image {
            width: 300px;
            height: 300px;
        }
        .scan-instruction {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .scan-detail {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .badge {
            background: #000;
            color: #fff;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .footer {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            border-top: 2px solid #000;
            padding-top: 20px;
            text-align: right;
        }
        .footer-info {
            float: left;
            text-align: left;
            font-size: 10px;
            color: #888;
            font-family: monospace;
        }
        .signature-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 60px;
        }
        .signature-name {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="border-frame">
                
                <div class="header">
                    <!-- Note: For DOMPDF, using absolute paths or base64 often works best for images -->
                    <img src="{{ $logo }}" class="logo" alt="Logo">
                    <div class="header-text">
                        <div class="header-subtitle">Smart Logbook</div>
                        <h1 class="header-title">UPT PERPUSTAKAAN</h1>
                        <div class="header-subtitle">Politeknik Negeri Sriwijaya</div>
                    </div>
                </div>

                <div class="qr-box">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" class="qr-image" alt="QR Code">
                </div>

                <div style="margin-bottom: 20px;">
                    <span class="badge">Buka Menu "Logbook" di Aplikasi</span>
                </div>

                <div class="scan-instruction">Scan Untuk Masuk</div>
                <div class="scan-detail">
                    Silakan scan QR Code ini untuk mencatat kehadiran kunjungan Anda<br>
                    pada aplikasi <strong>Self Service Perpustakaan</strong>.
                </div>

                <div class="footer">
                    <div class="footer-info">
                        Lat: {{ $lat }}<br>
                        Lng: {{ $lng }}<br>
                        Generated: {{ now()->format('d M Y H:i') }}
                    </div>
                    
                    <div style="display: inline-block; text-align: center;">
                        <div class="signature-title">Tertanda,<br>Kepala UPT Perpustakaan</div>
                        <div class="signature-name">_______________________</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
