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
    
    // Student specific
    public function getActiveLoansByUserId(int $userId): Collection;
    public function getHistoryLoansByUserId(int $userId, int $limit = 5): Collection;

    // Reporting & Analytics
    public function getFinesByDateRange(string $startDate, string $endDate); // Returns Builder/Paginator
    public function getTopViolators(string $startDate, string $endDate, int $limit = 5): Collection;
    public function getDailyFineStats(string $startDate, string $endDate): Collection;

    public function create(array $data): Loan;
    public function update(Loan $loan, array $data): Loan;
    public function findById(int $id): ?Loan;
}
