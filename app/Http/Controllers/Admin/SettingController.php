<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settings = $this->settingService->getAllSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        $this->settingService->updateSettings($data);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function printQr()
    {
        $settings = $this->settingService->getAllSettings();
        
        $password = env('LOGBOOK_QR_SECRET', 'secure-polsri-2025');
        $lat = $settings['library_lat']->value ?? '-2.9850';
        $lng = $settings['library_lng']->value ?? '104.7320';

        // Format: PASSWORD|LAT|LNG
        $qrContent = "{$password}|{$lat}|{$lng}";

        // Generate QR Code Server-Side
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(300)->errorCorrection('H')->generate($qrContent));

        return view('admin.settings.print-qr', compact('qrCode'));
    }

    public function downloadQrPdf()
    {
        $settings = $this->settingService->getAllSettings();
        
        $password = env('LOGBOOK_QR_SECRET', 'secure-polsri-2025');
        $lat = $settings['library_lat']->value ?? '-2.9850';
        $lng = $settings['library_lng']->value ?? '104.7320';

        // Format: PASSWORD|LAT|LNG
        $qrContent = "{$password}|{$lat}|{$lng}";

        // Generate QR Code as Base64 SVG
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(400)->errorCorrection('H')->generate($qrContent));

        // Encode Logo to Base64
        $logoPath = public_path('images/logo-polsri.png');
        $logoData = base64_encode(file_get_contents($logoPath));
        $logo = 'data:image/png;base64,' . $logoData;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.settings.pdf-qr', compact('qrCode', 'lat', 'lng', 'logo'));
        
        return $pdf->download('QR-Absensi-Perpustakaan.pdf');
    }
}