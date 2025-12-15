<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\LoanRepositoryInterface;
use App\Models\Loan;
use Illuminate\Http\Request;

class FineController extends Controller
{
    protected LoanRepositoryInterface $loanRepository;

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    /**
     * Show fine management dashboard
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'unpaid');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $userId = $request->get('user_id');
        $userSearch = $request->get('user_search');
        
        $stats = $this->loanRepository->getFinesStatistics();
        
        // Base query
        $query = \App\Models\Loan::with(['user', 'book'])
            ->where('status', \App\Enums\LoanStatus::RETURNED)
            ->where('fine_amount', '>', 0);
        
        // Filter by tab
        if ($tab === 'paid') {
            $query->whereRaw('"is_fine_paid" = true');
        } else {
            $query->whereRaw('"is_fine_paid" = false');
        }
        
        // Filter by date range
        if ($startDate !== null && $startDate !== '') {
            $query->whereDate('return_date', '>=', $startDate);
        }
        if ($endDate !== null && $endDate !== '') {
            $query->whereDate('return_date', '<=', $endDate);
        }
        
        // Filter by user
        if ($userId !== null && $userId !== '') {
            $query->where('user_id', (int)$userId);
        }
        
        // Default sort: terbaru
        $fines = $query->orderBy('return_date', 'desc')
            ->paginate(15)
            ->withQueryString();
        
        // Get users for dropdown
        $users = \App\Models\User::where('role', 'mahasiswa')
            ->orderBy('name')
            ->get();

        return view('admin.fines.index', compact('fines', 'stats', 'tab', 'users', 'startDate', 'endDate', 'userId', 'userSearch'));
    }

    /**
     * Mark fine as paid
     */
    public function markPaid(Loan $loan)
    {
        try {
            if ($loan->status !== \App\Enums\LoanStatus::RETURNED || $loan->fine_amount <= 0) {
                return back()->with('error', 'Transaksi tidak memiliki denda yang dapat ditandai lunas.');
            }

            $this->loanRepository->update($loan, [
                'is_fine_paid' => true,
            ]);

            return back()->with('success', 'Denda berhasil ditandai lunas. (Rp ' . number_format($loan->fine_amount, 0, ',', '.') . ')');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark fine as unpaid
     */
    public function markUnpaid(Loan $loan)
    {
        try {
            if ($loan->status !== \App\Enums\LoanStatus::RETURNED || $loan->fine_amount <= 0) {
                return back()->with('error', 'Transaksi tidak memiliki denda yang dapat ditandai belum lunas.');
            }

            $this->loanRepository->update($loan, [
                'is_fine_paid' => false,
            ]);

            return back()->with('success', 'Denda berhasil ditandai belum lunas.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show student fine history
     */
    public function studentFines(int $userId)
    {
        $student = \App\Models\User::findOrFail($userId);
        $fineStatus = $this->loanRepository->getStudentFineStatus($userId);

        return view('admin.fines.student', compact('student', 'fineStatus'));
    }
}
