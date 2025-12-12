<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $data = $this->reportService->getReportData($request->all());

        return view('admin.report.index', $data);
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));

        $data = $this->reportService->getExportData($request->all());

        $fileName = 'laporan-denda-' . $startDate . '-sd-' . $endDate . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Transaksi', 'Mahasiswa', 'NIM', 'Buku', 'Nominal Denda'];

        $callback = function() use($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->return_date->format('Y-m-d H:i:s'),
                    $row->transaction_code,
                    $row->user->name,
                    $row->user->nim,
                    $row->book->title,
                    $row->fine_amount
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        // Reuse the logic from index to get totals and list
        // Note: We might need to adjust getReportData to be reusable or just call the service again
        $data = $this->reportService->getReportData($request->all());
        
        // For PDF, we usually want *all* records, not just paginated ones, 
        // but getReportData likely returns paginated 'fines'. 
        // Let's check if we need to fetch all for export. 
        // Typically PDF reports should show everything filtered.
        // For now, we will use the same data to ensure consistency with what user sees,
        // but ideally we should fetch 'all' for print if pagination is small.
        // Let's use getExportData for the list (since it's all records) but we also need the totals.
        
        $exportData = $this->reportService->getExportData($request->all());
        
        // Merge the export data (Collection) into the view data, replacing the paginated 'fines'
        $data['fines'] = $exportData; 
        $data['startDate'] = $request->input('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
        $data['endDate'] = $request->input('end_date', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Encode Logo to Base64
        $logoPath = public_path('images/logo-polsri.png');
        $logoData = base64_encode(file_get_contents($logoPath));
        $data['logo'] = 'data:image/png;base64,' . $logoData;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.pdf', $data);
        $pdf->setPaper('a4', 'landscape'); // Reports often work better in landscape if there are many columns

        return $pdf->download('Laporan-Denda-Perpustakaan.pdf');
    }
}