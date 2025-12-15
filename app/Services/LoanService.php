<?php

namespace App\Services;

use App\Interfaces\LoanRepositoryInterface;
use App\Models\Loan;
use App\Models\Book;
use App\Enums\LoanStatus;
use App\Notifications\LoanDueSoonNotification;
use App\Notifications\LoanOverdueNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for managing book loans, returns, and self-service transactions.
 */
class LoanService
{
    /**
     * @var LoanRepositoryInterface
     */
    protected LoanRepositoryInterface $loanRepository;

    /**
     * @param LoanRepositoryInterface $loanRepository
     */
    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    /**
     * Check for due soon and overdue loans and send notifications.
     * This replaces the cron job.
     * 
     * @return array Result summary
     */
    public function checkAndSendNotifications(): array
    {
        $countDueSoon = 0;
        $countOverdue = 0;

        // 1. Check Due Soon (Tomorrow)
        $tomorrow = Carbon::tomorrow();
        $dueSoonLoans = Loan::with('user', 'book')
            ->where('status', LoanStatus::BORROWED)
            ->whereDate('due_date', $tomorrow)
            ->get();

        foreach ($dueSoonLoans as $loan) {
            try {
                $loan->user->notify(new LoanDueSoonNotification($loan));
                $countDueSoon++;
            } catch (\Exception $e) {
                Log::error("Failed to send due soon notification for loan {$loan->id}: " . $e->getMessage());
            }
        }

        // 2. Check Overdue (Today or Past)
        $overdueLoans = Loan::with('user', 'book')
            ->where('status', LoanStatus::BORROWED)
            ->whereDate('due_date', '<', Carbon::today())
            ->get();

        foreach ($overdueLoans as $loan) {
            try {
                $loan->user->notify(new LoanOverdueNotification($loan));
                $countOverdue++;
            } catch (\Exception $e) {
                Log::error("Failed to send overdue notification for loan {$loan->id}: " . $e->getMessage());
            }
        }

        Log::info("Loan Notification Check Run: {$countDueSoon} due soon, {$countOverdue} overdue.");

        return [
            'due_soon' => $countDueSoon,
            'overdue' => $countOverdue
        ];
    }

    /**
     * Get all loans with pagination and filters.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAllLoans(array $filters): LengthAwarePaginator
    {
        return $this->loanRepository->getAllPaginated($filters);
    }

    public function searchLoans(string $query)
    {
        return $this->loanRepository->searchLoans($query);
    }

    /**
     * Create a new loan transaction (Admin).
     *
     * @param array $data
     * @return Loan
     * @throws \Exception If stock is insufficient.
     */
    public function createLoan(array $data): Loan
    {
        return DB::transaction(function () use ($data) {
            $book = Book::findOrFail($data['book_id']);
            
            if ($book->stock < 1) {
                throw new \Exception('Stok buku habis.');
            }
            
            $book->decrement('stock');

            $data['transaction_code'] = 'TRX-' . strtoupper(Str::random(8));
            $data['status'] = LoanStatus::BORROWED;
            $data['borrow_date'] = Carbon::now();
            $data['due_date'] = Carbon::now()->addDays(7);

            return $this->loanRepository->create($data);
        });
    }

    /**
     * Create a self-service loan via QR scan (Student).
     *
     * @param int $userId
     * @param string $isbn
     * @return Loan
     * @throws \Exception If book not found, out of stock, or user already has active loan for the book.
     */
    public function createSelfServiceLoan(int $userId, string $isbn): Loan
    {
        $book = Book::where('isbn', $isbn)->first();
        
        if (!$book) {
            throw new \Exception('Buku dengan ISBN tersebut tidak ditemukan.');
        }

        if ($book->stock < 1) {
            throw new \Exception('Stok buku saat ini sedang kosong.');
        }

        $existingLoan = Loan::where('user_id', $userId)
                            ->where('book_id', $book->id)
                            ->whereIn('status', [LoanStatus::BORROWED, LoanStatus::PENDING_VALIDATION])
                            ->exists();

        if ($existingLoan) {
            throw new \Exception('Anda sedang meminjam buku ini.');
        }

        return DB::transaction(function () use ($userId, $book) {
            $book->decrement('stock');

            return $this->loanRepository->create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'transaction_code' => 'SELF-' . date('ymdHis') . '-' . rand(100, 999),
                'borrow_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(7),
                'status' => LoanStatus::PENDING_VALIDATION,
            ]);
        });
    }

    /**
     * Process a book return.
     *
     * @param Loan $loan
     * @return array Contains the updated loan and calculated fine amount.
     * @throws \Exception If book is already returned.
     */
    public function returnBook(Loan $loan): array
    {
        return DB::transaction(function () use ($loan) {
            if ($loan->status === LoanStatus::RETURNED) {
                throw new \Exception('Buku sudah dikembalikan.');
            }

            $returnDate = Carbon::now();
            $fineAmount = 0;
            
            // Get fine setting, strictly ensure it's a valid positive integer
            $settingValue = Setting::where('key', 'fine_per_day')->value('value');
            $finePerDay = intval($settingValue);
            if ($finePerDay <= 0) {
                $finePerDay = 1000; // Default fallback
            }

            $dateDue = $loan->due_date->copy()->startOfDay();
            $dateReturn = $returnDate->copy()->startOfDay();

            if ($dateReturn->greaterThan($dateDue)) {
                // Gunakan absolute = true untuk memastikan hasil positif
                $daysLate = $dateReturn->diffInDays($dateDue, true); 
                $fineAmount = $daysLate * $finePerDay;
            }

            $this->loanRepository->update($loan, [
                'status' => LoanStatus::RETURNED,
                'return_date' => $returnDate,
                'fine_amount' => max(0, $fineAmount), // Double safety: never less than 0
                'is_fine_paid' => $fineAmount == 0 ? true : false,
            ]);

            $loan->book->increment('stock');

            return [
                'loan' => $loan,
                'fine_amount' => $fineAmount
            ];
        });
    }
}