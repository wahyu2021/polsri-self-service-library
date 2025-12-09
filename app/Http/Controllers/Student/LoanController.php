<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\BookService;
use App\Services\LoanService;
use App\Services\UserService; // Assuming usage if needed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    protected LoanService $loanService;
    protected BookService $bookService;

    public function __construct(LoanService $loanService, BookService $bookService)
    {
        $this->loanService = $loanService;
        $this->bookService = $bookService;
    }

    // --- Halaman Scan & Borrow ---
    public function create()
    {
        return view('student.borrow');
    }

    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|string',
        ]);

        try {
            $loan = $this->loanService->createSelfServiceLoan(Auth::id(), $request->isbn);
            
            return redirect()->route('student.ticket.show', $loan->id)
                ->with('success', 'Peminjaman diajukan! Tunjukkan kode ini di pintu keluar.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // --- Lookup Buku via AJAX ---
    public function lookup(Request $request)
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

    // --- Halaman Exit Pass (Tiket) ---
    public function showTicket(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('student.ticket', compact('loan'));
    }

    public function checkStatus(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json(['status' => $loan->status]);
    }

    // --- Koleksi Saya ---
    public function collection()
    {
        $user = Auth::user();
        // Menggunakan repository via service kalau strict, tapi disini pakai Eloquent untuk kecepatan
        // Idealnya: $this->loanService->getUserActiveLoans($user->id);
        
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
