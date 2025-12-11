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

        return view('admin.settings.print-qr', compact('qrContent'));
    }
}