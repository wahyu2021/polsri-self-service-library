<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\ScanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    protected ScanService $scanService;

    public function __construct(ScanService $scanService)
    {
        $this->scanService = $scanService;
    }

    public function index()
    {
        return view('student.scan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $this->scanService->processCheckIn(
                Auth::user(),
                $request->qr_code,
                $request->latitude,
                $request->longitude
            );

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil! Selamat datang di perpustakaan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
