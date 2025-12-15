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

        // Fetch Real Database Notifications
        $user = \App\Models\User::find($userId);
        $notifications = $user->unreadNotifications;

        // Get fine summary data
        $fineData = $this->getStudentFinesSummary($userId);

        return [
            'activeLoans' => $activeLoans,
            'historyLoans' => $historyLoans,
            'notifications' => $notifications,
            'fineData' => $fineData,
        ];
    }

    /**
     * Get student fine summary
     */
    public function getStudentFinesSummary(int $userId): array
    {
        $unpaidFines = \App\Models\Loan::where('user_id', $userId)
            ->where('status', 'returned')
            ->where('fine_amount', '>', 0)
            ->whereRaw('"is_fine_paid" = false')
            ->get();

        $paidFines = \App\Models\Loan::where('user_id', $userId)
            ->where('status', 'returned')
            ->where('fine_amount', '>', 0)
            ->whereRaw('"is_fine_paid" = true')
            ->get();

        return [
            'unpaid_count' => $unpaidFines->count(),
            'unpaid_total' => $unpaidFines->sum('fine_amount'),
            'paid_count' => $paidFines->count(),
            'paid_total' => $paidFines->sum('fine_amount'),
            'has_unpaid' => $unpaidFines->count() > 0,
        ];
    }
}

