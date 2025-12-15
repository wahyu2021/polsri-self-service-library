<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLoanRequest;
use App\Http\Requests\Admin\UpdateLoanRequest;
use App\Models\Loan;
use App\Services\BookService;
use App\Services\LoanService;
use App\Services\UserService;
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
        if ($request->has('q')) {
            $loans = $this->loanService->searchLoans($request->get('q'));
            
            // Format data for suggestion UI
            $formatted = $loans->map(function($loan) {
                return [
                    'id' => $loan->id,
                    'title' => $loan->transaction_code . ' - ' . $loan->user->name, // Main text
                    'isbn' => $loan->book->title, // Sub text (using existing UI structure)
                    'search_term' => $loan->transaction_code, // Clean value for search input
                ];
            });

            return response()->json(['success' => true, 'data' => $formatted]);
        }

        $loans = $this->loanService->getAllLoans($request->all());

        if ($request->ajax()) {
            return view('admin.circulation._table', compact('loans'));
        }

        return view('admin.circulation.index', compact('loans'));
    }

    public function create()
    {
        $users = $this->userService->getStudents();
        $books = $this->bookService->getAvailableBooks();

        return view('admin.circulation.create', compact('users', 'books'));
    }

    public function searchUser(Request $request)
    {
        if ($request->has('q')) {
            $users = $this->userService->searchStudents($request->get('q'));
            return response()->json(['success' => true, 'data' => $users]);
        }

        $nim = $request->get('nim');
        $user = $this->userService->findStudentByNim($nim);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan atau bukan role mahasiswa.',
            ]);
        }

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function searchBook(Request $request)
    {
        if ($request->has('q')) {
            $books = $this->bookService->searchBooks($request->get('q'));
            return response()->json(['success' => true, 'data' => $books]);
        }

        $isbn = $request->get('isbn');
        $book = $this->bookService->findBookByIsbn($isbn);

        if (! $book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan.']);
        }

        if ($book->stock < 1) {
            return response()->json(['success' => false, 'message' => 'Stok buku habis.']);
        }

        return response()->json(['success' => true, 'data' => $book]);
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

    public function edit(Loan $loan)
    {
        return view('admin.circulation.edit', compact('loan'));
    }

    public function update(UpdateLoanRequest $request, Loan $loan)
    {
        try {
            // Manual update for admin correction
            $data = $request->validated();
            
            // Logic tambahan: Jika status diubah jadi bukan RETURNED, null-kan return_date jika admin mengosongkannya
            if ($data['status'] !== \App\Enums\LoanStatus::RETURNED->value && empty($data['return_date'])) {
                $data['return_date'] = null;
                $data['fine_amount'] = 0; // Reset fine if not returned
            }

            $loan->update($data);

            return redirect()->route('admin.loans.index')
                ->with('success', 'Data peminjaman berhasil diperbarui.');
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
                'status' => \App\Enums\LoanStatus::BORROWED,
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil disetujui. Mahasiswa diizinkan keluar.',
                ]);
            }

            // Fix: Prevent redirecting to AJAX endpoint if polling updated the session's previous URL
            if (str_contains(url()->previous(), 'validation-queue')) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Peminjaman mandiri berhasil diverifikasi. Mahasiswa diizinkan keluar.');
            }

            return back()->with('success', 'Peminjaman mandiri berhasil diverifikasi. Mahasiswa diizinkan keluar.');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            if (str_contains(url()->previous(), 'validation-queue')) {
                return redirect()->route('admin.dashboard')
                    ->with('error', $e->getMessage());
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function returnBook(Loan $loan)
    {
        try {
            $result = $this->loanService->returnBook($loan);

            $message = 'Buku berhasil dikembalikan.';
            if ($result['fine_amount'] > 0) {
                $message .= ' Denda: Rp '.number_format($result['fine_amount'], 0, ',', '.');
            }

            return redirect()->route('admin.loans.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
