<?php

namespace App\Services;

use App\Interfaces\LoanRepositoryInterface;
use App\Interfaces\LogbookRepositoryInterface;
use App\Interfaces\BookRepositoryInterface;
use Carbon\Carbon;

class DashboardService
{
    protected LoanRepositoryInterface $loanRepository;
    protected LogbookRepositoryInterface $logbookRepository;
    protected BookRepositoryInterface $bookRepository;

    public function __construct(
        LoanRepositoryInterface $loanRepository,
        LogbookRepositoryInterface $logbookRepository,
        BookRepositoryInterface $bookRepository
    ) {
        $this->loanRepository = $loanRepository;
        $this->logbookRepository = $logbookRepository;
        $this->bookRepository = $bookRepository;
    }

    public function getAdminStats(): array
    {
        return [
            'visitorsToday' => $this->logbookRepository->getVisitorsCountByDate(Carbon::today()),
            'activeLoans' => $this->loanRepository->getActiveLoansCount(),
            'overdueBooks' => $this->loanRepository->getOverdueLoansCount(),
            'todayFines' => $this->loanRepository->getTodayFinesTotal(),
            'validationQueue' => $this->loanRepository->getPendingValidation(),
            'recentLogbooks' => $this->logbookRepository->getRecentEntries(6),
            'chartData' => $this->logbookRepository->getWeeklyStats(),
            'recentTransactions' => $this->loanRepository->getRecentTransactions(5),
            'popularBooks' => $this->bookRepository->getPopularBooks(5),
        ];
    }

    public function getValidationQueue()
    {
        return $this->loanRepository->getPendingValidation();
    }
}
