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

class LoanService
{
    protected LoanRepositoryInterface $loanRepository;

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function getAllLoans(array $filters)
    {
        return $this->loanRepository->getAllPaginated($filters);
    }

    public function createLoan(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Business Logic: Decrement Stock
            $book = Book::findOrFail($data['book_id']);
            
            // Double check stock
            if ($book->stock < 1) {
                throw new \Exception('Stok buku habis.');
            }
            
            $book->decrement('stock');

            // Prepare Data
            $data['transaction_code'] = 'TRX-' . strtoupper(Str::random(8));
            $data['status'] = LoanStatus::BORROWED;
            $data['borrow_date'] = Carbon::now();
            $data['due_date'] = Carbon::now()->addDays(7);

            return $this->loanRepository->create($data);
        });
    }

    public function createSelfServiceLoan(int $userId, string $isbn)
    {
        // 1. Cari Buku by ISBN (atau kode buku lokal)
        $book = Book::where('isbn', $isbn)->first();
        
        if (!$book) {
            throw new \Exception('Buku dengan ISBN tersebut tidak ditemukan.');
        }

        if ($book->stock < 1) {
            throw new \Exception('Stok buku saat ini sedang kosong.');
        }

        // 2. Cek apakah user sudah meminjam buku yang sama dan belum kembali
        $existingLoan = Loan::where('user_id', $userId)
                            ->where('book_id', $book->id)
                            ->whereIn('status', [LoanStatus::BORROWED, LoanStatus::PENDING_VALIDATION])
                            ->exists();

        if ($existingLoan) {
            throw new \Exception('Anda sedang meminjam buku ini.');
        }

        // 3. Buat Transaksi Pending
        return DB::transaction(function () use ($userId, $book) {
            // Stok dikurangi langsung agar tidak di-checkout orang lain
            $book->decrement('stock');

            return $this->loanRepository->create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'transaction_code' => 'SELF-' . date('ymdHis') . '-' . rand(100, 999),
                'loan_date' => Carbon::now(), // Gunakan loan_date agar konsisten dengan createLoan
                'borrow_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(7),
                'status' => LoanStatus::PENDING_VALIDATION, // Butuh scan admin di pintu keluar
            ]);
        });
    }

    public function returnBook(Loan $loan)
    {
        return DB::transaction(function () use ($loan) {
            if ($loan->status === LoanStatus::RETURNED) {
                throw new \Exception('Buku sudah dikembalikan.');
            }

            $returnDate = Carbon::now();
            $fineAmount = 0;
            
            // Get Fine Rate from Settings (or default)
            $finePerDay = Setting::where('key', 'fine_per_day')->value('value') ?? 1000;

            if ($returnDate->greaterThan($loan->due_date)) {
                $daysLate = $returnDate->diffInDays($loan->due_date);
                $fineAmount = $daysLate * $finePerDay;
            }

            // Update Loan
            $this->loanRepository->update($loan, [
                'status' => LoanStatus::RETURNED,
                'return_date' => $returnDate,
                'fine_amount' => $fineAmount,
            ]);

            // Increment Stock
            $loan->book->increment('stock');

            return [
                'loan' => $loan,
                'fine_amount' => $fineAmount
            ];
        });
    }
}
