<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\BookService;
use App\Services\LoanService;
use App\Http\Requests\Student\StoreLoanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Handles student loan operations including scanning, borrowing, and viewing collections.
 */
class LoanController extends Controller
{
    /**
     * @var LoanService
     */
    protected LoanService $loanService;

    /**
     * @var BookService
     */
    protected BookService $bookService;

    /**
     * @param LoanService $loanService
     * @param BookService $bookService
     */
    public function __construct(LoanService $loanService, BookService $bookService)
    {
        $this->loanService = $loanService;
        $this->bookService = $bookService;
    }

    /**
     * Show the scan and borrow interface.
     *
     * @return View
     */
    public function create(): View
    {
        return view('student.borrow');
    }

    /**
     * Store a new loan request.
     *
     * @param StoreLoanRequest $request
     * @return RedirectResponse
     */
    public function store(StoreLoanRequest $request): RedirectResponse
    {
        try {
            $loan = $this->loanService->createSelfServiceLoan(Auth::id(), $request->isbn);
            
            return redirect()->route('student.ticket.show', $loan->id)
                ->with('success', 'Peminjaman diajukan! Tunjukkan kode ini di pintu keluar.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Look up book details via ISBN.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function lookup(Request $request): JsonResponse
    {
        $request->validate(['isbn' => 'required|string']);
        
        $book = \App\Models\Book::where('isbn', $request->isbn)->first();

        if (!$book) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found' => true,
            'title' => $book->title,
            'author' => $book->author,
            'cover' => $book->cover ? asset('storage/'.$book->cover) : null,
            'stock' => $book->stock
        ]);
    }

    /**
     * Display the exit pass (ticket) for a loan.
     *
     * @param Loan $loan
     * @return View
     */
    public function showTicket(Loan $loan): View
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('student.ticket', compact('loan'));
    }

    /**
     * Check the current status of a loan.
     *
     * @param Loan $loan
     * @return JsonResponse
     */
    public function checkStatus(Loan $loan): JsonResponse
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json(['status' => $loan->status]);
    }

    /**
     * Display the student's book collection and history.
     *
     * @return View
     */
    public function collection(): View
    {
        $user = Auth::user();
        
        $activeLoans = $user->loans()
                            ->with('book')
                            ->whereIn('status', ['borrowed', 'pending_validation'])
                            ->orderBy('due_date', 'asc')
                            ->get();

        $historyLoans = $user->loans()
                             ->with('book')
                             ->where('status', 'returned')
                             ->latest('return_date')
                             ->limit(10)
                             ->get();

        return view('student.collection', compact('activeLoans', 'historyLoans'));
    }
}