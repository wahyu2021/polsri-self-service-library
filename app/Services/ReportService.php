<?php

namespace App\Services;

use App\Interfaces\LoanRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportService
{
    protected LoanRepositoryInterface $loanRepository;

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function getReportData(array $filters)
    {
        $startDate = $filters['start_date'] ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $filters['end_date'] ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // Query Base via Repository
        $queryBuilder = $this->loanRepository->getFinesByDateRange($startDate, $endDate);
        
        // Clone for stats before pagination
        $statsQuery = clone $queryBuilder;
        $totalRevenue = $statsQuery->sum('fine_amount');
        $totalTransactions = $statsQuery->count();
        $averageFine = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Pagination for table
        $fines = $queryBuilder->latest('return_date')->paginate(15);

        // Top Violators
        $topViolators = $this->loanRepository->getTopViolators($startDate, $endDate);

        // Daily Stats for Chart
        $dailyStats = $this->loanRepository->getDailyFineStats($startDate, $endDate)->keyBy('date');

        // Prepare chart data
        $chartData = [];
        $period = CarbonPeriod::create($startDate, $endDate);
        
        $maxChartValue = 0;

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $amount = isset($dailyStats[$dateString]) ? $dailyStats[$dateString]->total : 0;
            
            $chartData[] = [
                'date' => $date->format('d M'),
                'amount' => $amount
            ];

            if ($amount > $maxChartValue) {
                $maxChartValue = $amount;
            }
        }

        return compact(
            'fines', 
            'totalRevenue', 
            'totalTransactions', 
            'averageFine', 
            'topViolators',
            'chartData',
            'maxChartValue',
            'startDate',
            'endDate'
        );
    }
    
    public function getExportData(array $filters)
    {
        $startDate = $filters['start_date'] ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $filters['end_date'] ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // Query Base via Repository
        $queryBuilder = $this->loanRepository->getFinesByDateRange($startDate, $endDate);
        
        // Get all data for export
        return $queryBuilder->latest('return_date')->get();
    }
}
