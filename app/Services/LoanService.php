<?php

namespace App\Services;

use App\Interfaces\LoanRepositoryInterface;
use App\Models\Loan;
use App\Models\Book;
use App\Enums\LoanStatus;
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
            
            $finePerDay = Setting::where('key', 'fine_per_day')->value('value') ?? 1000;

            $dateDue = $loan->due_date->copy()->startOfDay();
            $dateReturn = $returnDate->startOfDay();

            if ($dateReturn->greaterThan($dateDue)) {
                $daysLate = abs($dateReturn->diffInDays($dateDue, false));
                $fineAmount = $daysLate * $finePerDay;
            }

            $this->loanRepository->update($loan, [
                'status' => LoanStatus::RETURNED,
                'return_date' => $returnDate,
                'fine_amount' => $fineAmount,
            ]);

            $loan->book->increment('stock');

            return [
                'loan' => $loan,
                'fine_amount' => $fineAmount
            ];
        });
    }
}