<?php

namespace App\Interfaces;

use App\Models\Loan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface LoanRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function getActiveLoansCount(): int;
    public function getOverdueLoansCount(): int;
    public function getPendingValidation(): Collection;
    public function getRecentTransactions(int $limit = 5): Collection;
    public function searchLoans(string $query, int $limit = 5): Collection;
    
    // Student specific
    public function getActiveLoansByUserId(int $userId): Collection;
    public function getHistoryLoansByUserId(int $userId, int $limit = 5): Collection;
    
    // Notifications (Real Implementation)
    public function getOverdueLoansByUserId(int $userId): Collection;
    public function getDueSoonLoansByUserId(int $userId, int $days = 1): Collection;
    public function getUnpaidFinesByUserId(int $userId): Collection;

    // Reporting & Analytics
    public function getFinesByDateRange(string $startDate, string $endDate); // Returns Builder/Paginator
    public function getTopViolators(string $startDate, string $endDate, int $limit = 5): Collection;
    public function getDailyFineStats(string $startDate, string $endDate): Collection;

    public function create(array $data): Loan;
    public function update(Loan $loan, array $data): Loan;
    public function findById(int $id): ?Loan;
}
