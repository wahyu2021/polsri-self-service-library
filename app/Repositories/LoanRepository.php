<?php

namespace App\Repositories;

use App\Interfaces\LoanRepositoryInterface;
use App\Models\Loan;
use App\Enums\LoanStatus;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Support\Facades\DB;

class LoanRepository implements LoanRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Loan::with(['user', 'book']);

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  })
                  ->orWhereHas('book', function($b) use ($search) {
                      $b->where('title', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getActiveLoansCount(): int
    {
        return Loan::where('status', LoanStatus::BORROWED)->count();
    }

    public function getOverdueLoansCount(): int
    {
        return Loan::where('status', LoanStatus::BORROWED)
                   ->where('due_date', '<', Carbon::today())
                   ->count();
    }

    public function getPendingValidation(): Collection
    {
        return Loan::with(['user', 'book'])
                   ->where('status', LoanStatus::PENDING_VALIDATION)
                   ->orderBy('created_at', 'asc')
                   ->limit(5)
                   ->get();
    }

    public function getRecentTransactions(int $limit = 5): Collection
    {
        return Loan::with(['user', 'book'])
                   ->where('status', '!=', LoanStatus::PENDING_VALIDATION)
                   ->orderBy('updated_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public function searchLoans(string $query, int $limit = 5): Collection
    {
        return Loan::with(['user', 'book'])
            ->where(function($q) use ($query) {
                $q->where('transaction_code', 'like', "%{$query}%")
                  ->orWhereHas('user', function($u) use ($query) {
                      $u->where('name', 'like', "%{$query}%")
                        ->orWhere('nim', 'like', "%{$query}%");
                  })
                  ->orWhereHas('book', function($b) use ($query) {
                      $b->where('title', 'like', "%{$query}%")
                        ->orWhere('isbn', 'like', "%{$query}%");
                  });
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getActiveLoansByUserId(int $userId): Collection
    {
        return Loan::with('book')
                   ->where('user_id', $userId)
                   ->where('status', LoanStatus::BORROWED)
                   ->get();
    }

    public function getHistoryLoansByUserId(int $userId, int $limit = 5): Collection
    {
        return Loan::with('book')
                   ->where('user_id', $userId)
                   ->where('status', LoanStatus::RETURNED)
                   ->latest('return_date')
                   ->limit($limit)
                   ->get();
    }

    public function getOverdueLoansByUserId(int $userId): Collection
    {
        return Loan::with('book')
                   ->where('user_id', $userId)
                   ->where('status', LoanStatus::BORROWED)
                   ->where('due_date', '<', Carbon::now())
                   ->get();
    }

    public function getDueSoonLoansByUserId(int $userId, int $days = 1): Collection
    {
        $targetDate = Carbon::now()->addDays($days);
        
        return Loan::with('book')
                   ->where('user_id', $userId)
                   ->where('status', LoanStatus::BORROWED)
                   ->whereBetween('due_date', [Carbon::now(), $targetDate])
                   ->get();
    }

    public function getUnpaidFinesByUserId(int $userId): Collection
    {
        return Loan::with('book')
                   ->where('user_id', $userId)
                   ->where('status', LoanStatus::RETURNED)
                   ->where('fine_amount', '>', 0)
                   ->where('is_fine_paid', false)
                   ->get();
    }

    public function getFinesByDateRange(string $startDate, string $endDate)
    {
        return Loan::with(['user', 'book'])
                   ->where('status', LoanStatus::RETURNED)
                   ->where('fine_amount', '>', 0)
                   ->whereBetween('return_date', [$startDate, $endDate]);
    }

    public function getTopViolators(string $startDate, string $endDate, int $limit = 5): Collection
    {
        return Loan::select('user_id', DB::raw('sum(fine_amount) as total_fine'), DB::raw('count(*) as total_late'))
                    ->where('status', LoanStatus::RETURNED)
                    ->where('fine_amount', '>', 0)
                    ->whereBetween('return_date', [$startDate, $endDate])
                    ->groupBy('user_id')
                    ->with('user')
                    ->orderByDesc('total_fine')
                    ->limit($limit)
                    ->get();
    }

    public function getDailyFineStats(string $startDate, string $endDate): Collection
    {
        return Loan::select(DB::raw('DATE(return_date) as date'), DB::raw('sum(fine_amount) as total'))
                  ->where('status', LoanStatus::RETURNED)
                  ->where('fine_amount', '>', 0)
                  ->whereBetween('return_date', [$startDate, $endDate])
                  ->groupBy('date')
                  ->orderBy('date', 'asc')
                  ->get();
    }

    public function create(array $data): Loan
    {
        return Loan::create($data);
    }

    public function update(Loan $loan, array $data): Loan
    {
        $loan->update($data);
        return $loan;
    }

    public function findById(int $id): ?Loan
    {
        return Loan::with(['user', 'book'])->find($id);
    }
}
