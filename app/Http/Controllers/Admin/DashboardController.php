<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\LoanService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;
    protected LoanService $loanService;

    public function __construct(DashboardService $dashboardService, LoanService $loanService)
    {
        $this->dashboardService = $dashboardService;
        $this->loanService = $loanService;
    }

    public function index()
    {
        // Lazy Notification Trigger (replaces cron job)
        // Checks only once per day when an admin visits the dashboard
        $today = Carbon::today()->toDateString();
        $cacheKey = "loan_notifications_checked_{$today}";

        if (!Cache::has($cacheKey)) {
            // Run the check
            $this->loanService->checkAndSendNotifications();
            
            // Mark as checked for today
            Cache::put($cacheKey, true, Carbon::tomorrow());
        }

        $stats = $this->dashboardService->getAdminStats();

        return view('admin.dashboard', $stats);
    }
}