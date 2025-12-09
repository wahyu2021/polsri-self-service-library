<?php

namespace App\Services;

use App\Interfaces\LoanRepositoryInterface;
use Illuminate\Support\Collection;

class StudentService
{
    protected LoanRepositoryInterface $loanRepository;

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function getStudentDashboardData(int $userId): array
    {
        $activeLoans = $this->loanRepository->getActiveLoansByUserId($userId);
        $historyLoans = $this->loanRepository->getHistoryLoansByUserId($userId);

        return [
            'activeLoans' => $activeLoans,
            'historyLoans' => $historyLoans,
        ];
    }
}
