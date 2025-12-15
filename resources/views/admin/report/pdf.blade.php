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
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.15;
            color: #000;
        }

        /* ================== KOP SURAT ================== */
        .kop-container {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .cell-logo {
            width: 90px;
            text-align: center;
        }

        .logo-img {
            width: 85px;
            height: auto;
        }

        .cell-text {
            text-align: center;
            padding: 0 10px;
        }

        .text-kementrian {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .text-politeknik {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }

        .text-upt {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .text-alamat {
            font-size: 9pt;
            margin-top: 4px;
            line-height: 1.3;
        }

        /* ================== JUDUL ================== */
        .judul-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-main {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .judul-periode {
            font-size: 11pt;
            margin-top: 4px;
        }

        /* ================== RINGKASAN ================== */
        .summary-box {
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 20px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            border-right: 1px solid #000;
            text-align: center;
            padding: 8px;
        }

        .summary-table td:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 9pt;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 11pt;
            font-weight: bold;
        }

        /* ================== TABEL DATA ================== */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
        }

        table.data-table th {
            background: #f0f0f0;
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
            background: #f9f9f9;
        }

        /* ================== TANDA TANGAN ================== */
        .signature-container {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
        }

        .signer-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

<!-- ================== KOP ================== -->
<div class="kop-container">
    <table class="kop-table">
        <tr>
            <td class="cell-logo">
                <img src="{{ $logo }}" class="logo-img" alt="Logo Polsri">
            </td>
            <td class="cell-text">
                <div class="text-kementrian">
                    Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi
                </div>
                <div class="text-politeknik">
                    Politeknik Negeri Sriwijaya
                </div>
                <div class="text-upt">
                    UPT Perpustakaan
                </div>
                <div class="text-alamat">
                    Jalan Srijaya Negara, Bukit Besar, Palembang 30139 Telepon (0711) 353414<br>
                    Laman: www.polsri.ac.id | Pos-el: info@polsri.ac.id
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- ================== JUDUL ================== -->
<div class="judul-wrapper">
    <div class="judul-main">
        Laporan Keuangan & Denda Perpustakaan
    </div>
    <div class="judul-periode">
        Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }}
        s.d.
        {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}
    </div>
</div>

<!-- ================== RINGKASAN ================== -->
<div class="summary-box">
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Total Pendapatan Denda</div>
                <div class="summary-value">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
            </td>
            <td>
                <div class="summary-label">Jumlah Transaksi Terlambat</div>
                <div class="summary-value">
                    {{ $totalTransactions }} Transaksi
                </div>
            </td>
            <td>
                <div class="summary-label">Rata-rata Denda</div>
                <div class="summary-value">
                    Rp {{ number_format($averageFine, 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- ================== TABEL DATA ================== -->
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
                <strong>{{ $fine->user->name }}</strong><br>
                <span style="font-size:9pt;">NIM. {{ $fine->user->nim }}</span>
            </td>
            <td class="col-book">{{ $fine->book->title }}</td>
            <td class="col-fine">
                {{ number_format($fine->fine_amount, 0, ',', '.') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;font-style:italic;padding:20px;">
                Tidak ada data transaksi denda pada periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>

    @if(count($fines) > 0)
    <tfoot>
        <tr class="total-row">
            <td colspan="5" style="text-align:right;">Total Pendapatan</td>
            <td class="col-fine">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
    @endif
</table>

<!-- ================== TANDA TANGAN ================== -->
<div class="signature-container">
    <table class="signature-table">
        <tr>
            <td width="70%"></td>
            <td width="30%">
                Palembang, {{ now()->isoFormat('D MMMM Y') }}<br><br>
                <strong>Kepala UPT Perpustakaan</strong><br><br><br>
                <div class="signer-name">
                    {{ Auth::user()->name ?? 'NAMA KEPALA' }}
                </div>
                NIP. ............................
            </td>
        </tr>
    </table>
</div>

</body>
</html>
