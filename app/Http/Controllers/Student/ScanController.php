<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\ScanService;
use App\Http\Requests\Student\StoreLogbookRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Handles student attendance and logbook scanning operations.
 */
class ScanController extends Controller
{
    /**
     * @var ScanService
     */
    protected ScanService $scanService;

    /**
     * @param ScanService $scanService
     */
    public function __construct(ScanService $scanService)
    {
        $this->scanService = $scanService;
    }

    /**
     * Display the logbook scanning interface.
     *
     * @return View
     */
    public function index(): View
    {
        return view('student.logbook');
    }

    /**
     * Process the student check-in request.
     *
     * @param StoreLogbookRequest $request
     * @return JsonResponse
     */
    public function store(StoreLogbookRequest $request): JsonResponse
    {
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