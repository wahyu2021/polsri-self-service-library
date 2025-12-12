<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan & Denda</title>
    <style>
        @page {
            margin: 1cm 2cm 2cm 2cm;
            size: A4 landscape;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.15;
            color: #000;
        }
        
        /* Header / Kop Surat Standard Akademik */
        .kop-header {
            width: 100%;
            margin-bottom: 2px;
            border-bottom: 5px double #000; /* Garis ganda tebal-tipis khas surat dinas */
            padding-bottom: 10px;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
        }
        .cell-logo {
            width: 15%;
            text-align: center;
        }
        .cell-text {
            width: 85%;
            text-align: center;
            padding-right: 15%; /* Kompensasi agar teks benar-benar di tengah optik halaman, bukan tengah sisa cell */
        }
        .logo-img {
            width: 90px;
            height: auto;
        }
        
        /* Tipografi Kop Surat */
        .kop-text-1 {
            font-size: 12pt;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .kop-text-2 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin: 2px 0;
        }
        .kop-text-3 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .kop-text-alamat {
            font-size: 9pt;
            margin-top: 5px;
            line-height: 1.2;
        }

        /* Judul Laporan */
        .judul-wrapper {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .judul-main {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .judul-periode {
            font-size: 11pt;
            margin-top: 5px;
        }

        /* Ringkasan / Summary */
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #000;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px;
            text-align: center;
            border-right: 1px solid #000;
        }
        .summary-table td:last-child {
            border-right: none;
        }
        .summary-label {
            font-size: 9pt;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 11pt;
            font-weight: bold;
        }

        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 10pt;
        }
        table.data-table th {
            background-color: #f0f0f0; 
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .col-no { width: 5%; text-align: center; }
        .col-date { width: 12%; text-align: center; }
        .col-trans { width: 15%; text-align: center; }
        .col-mhs { width: 25%; }
        .col-book { width: 28%; }
        .col-fine { width: 15%; text-align: right; }
        
        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        /* Tanda Tangan */
        .signature-container {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid; /* Jangan memutus tanda tangan ke halaman baru */
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            vertical-align: top;
            text-align: center;
        }
        .signature-space {
            height: 70px;
        }
        .signer-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header / Kop Surat -->
    <div class="kop-header">
        <table class="kop-table">
            <tr>
                <td class="cell-logo">
                    <!-- Pastikan logo berlatar transparan/putih -->
                    <img src="{{ $logo }}" class="logo-img" alt="Logo Polsri">
                </td>
                <td class="cell-text">
                    <div class="kop-text-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN,<br>RISET, DAN TEKNOLOGI</div>
                    <div class="kop-text-2">POLITEKNIK NEGERI SRIWIJAYA</div>
                    <div class="kop-text-3">UPT PERPUSTAKAAN</div>
                    <div class="kop-text-alamat">
                        Jalan Srijaya Negara Bukit Besar, Palembang 30139<br>
                        Telepon: (0711) 353414 Faksimile: (0711) 355918<br>
                        Laman: www.polsri.ac.id Pos-el: info@polsri.ac.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Judul -->
    <div class="judul-wrapper">
        <div class="judul-main">LAPORAN KEUANGAN & DENDA PERPUSTAKAAN</div>
        <div class="judul-periode">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</div>
    </div>

    <!-- Ringkasan Eksekutif -->
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>
                    <div class="summary-label">Total Pendapatan Denda</div>
                    <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="summary-label">Jumlah Transaksi Terlambat</div>
                    <div class="summary-value">{{ $totalTransactions }} Transaksi</div>
                </td>
                <td>
                    <div class="summary-label">Rata-rata Denda</div>
                    <div class="summary-value">Rp {{ number_format($averageFine, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data Utama -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-date">Tanggal Kembali</th>
                <th class="col-trans">Kode Transaksi</th>
                <th class="col-mhs">Mahasiswa</th>
                <th class="col-book">Judul Buku</th>
                <th class="col-fine">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fines as $index => $fine)
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-date">{{ $fine->return_date->format('d/m/Y') }}</td>
                <td class="col-trans">{{ $fine->transaction_code }}</td>
                <td class="col-mhs">
                    <div style="font-weight: bold;">{{ $fine->user->name }}</div>
                    <div style="font-size: 8pt; color: #333;">NIM. {{ $fine->user->nim }}</div>
                </td>
                <td class="col-book">{{ $fine->book->title }}</td>
                <td class="col-fine">{{ number_format($fine->fine_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; font-style: italic;">
                    Tidak ada data transaksi denda pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            @if(count($fines) > 0)
            <tr class="total-row">
                <td colspan="5" style="text-align: right; text-transform: uppercase;">Total Pendapatan</td>
                <td class="col-fine">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td width="65%"></td> <!-- Spacer kiri -->
                <td width="35%">
                    <div style="margin-bottom: 5px;">Palembang, {{ now()->isoFormat('D MMMM Y') }}</div>
                    <div style="font-weight: bold; margin-bottom: 20px;">Kepala UPT Perpustakaan</div>
                    
                    <div class="signature-space">
                        <!-- Space untuk TTD basah / stempel -->
                    </div>

                    <div class="signer-name">{{ Auth::user()->name ?? 'NAMA KEPALA' }}</div>
                    <div>NIP. ...........................</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
