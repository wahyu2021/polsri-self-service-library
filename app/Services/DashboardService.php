<?php

namespace App\Services;

use App\Interfaces\LoanRepositoryInterface;
use App\Interfaces\LogbookRepositoryInterface;
use Carbon\Carbon;

class DashboardService
{
    protected LoanRepositoryInterface $loanRepository;
    protected LogbookRepositoryInterface $logbookRepository;

    public function __construct(
        LoanRepositoryInterface $loanRepository,
        LogbookRepositoryInterface $logbookRepository
    ) {
        $this->loanRepository = $loanRepository;
        $this->logbookRepository = $logbookRepository;
    }

    public function getAdminStats(): array
    {
        return [
            'visitorsToday' => $this->logbookRepository->getVisitorsCountByDate(Carbon::today()),
            'activeLoans' => $this->loanRepository->getActiveLoansCount(),
            'overdueBooks' => $this->loanRepository->getOverdueLoansCount(),
            'validationQueue' => $this->loanRepository->getPendingValidation(),
            'recentLogbooks' => $this->logbookRepository->getRecentEntries(10),
        ];
    }

    public function getValidationQueue()
    {
        return $this->loanRepository->getPendingValidation();
    }
}
