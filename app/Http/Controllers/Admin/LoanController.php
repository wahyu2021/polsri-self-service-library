<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\LoanService;
use App\Services\UserService;
use App\Services\BookService;
use App\Http\Requests\Admin\StoreLoanRequest;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    protected LoanService $loanService;
    protected UserService $userService;
    protected BookService $bookService;

    public function __construct(
        LoanService $loanService,
        UserService $userService,
        BookService $bookService
    ) {
        $this->loanService = $loanService;
        $this->userService = $userService;
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        $loans = $this->loanService->getAllLoans($request->all());
        return view('admin.circulation.index', compact('loans'));
    }

    public function create()
    {
        $users = $this->userService->getStudents();
        $books = $this->bookService->getAvailableBooks();
        
        return view('admin.circulation.create', compact('users', 'books'));
    }

    public function store(StoreLoanRequest $request)
    {
        try {
            $this->loanService->createLoan($request->validated());
            return redirect()->route('admin.loans.index')
                ->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function approve(Loan $loan)
    {
        try {
            if ($loan->status !== \App\Enums\LoanStatus::PENDING_VALIDATION) {
                throw new \Exception('Hanya peminjaman status pending yang bisa disetujui.');
            }

            // Update status menjadi BORROWED (Aktif)
            $loan->update([
                'status' => \App\Enums\LoanStatus::BORROWED
            ]);

            return back()->with('success', 'Peminjaman mandiri berhasil diverifikasi. Mahasiswa diizinkan keluar.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function returnBook(Loan $loan)
    {
        try {
            $result = $this->loanService->returnBook($loan);
            
            $message = 'Buku berhasil dikembalikan.';
            if ($result['fine_amount'] > 0) {
                $message .= ' Denda: Rp ' . number_format($result['fine_amount'], 0, ',', '.');
            }

            return redirect()->route('admin.loans.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}