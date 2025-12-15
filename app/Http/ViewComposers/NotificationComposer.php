<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\LoanRepositoryInterface;
use Carbon\Carbon;

class NotificationComposer
{
    protected LoanRepositoryInterface $loanRepository;

    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }

        $count = 0;

        if (Auth::user()->role->value === 'mahasiswa') {
            // Untuk Mahasiswa: Notifikasi sistem (denda, jatuh tempo, dll)
            $count = Auth::user()->unreadNotifications()->count();
        } elseif (Auth::user()->role->value === 'admin') {
            // Untuk Admin: Jumlah peminjaman yang menunggu persetujuan (Pending)
            $count = \App\Models\Loan::where('status', \App\Enums\LoanStatus::PENDING_VALIDATION)->count();
        }

        $view->with('globalNotificationCount', $count);
    }
}
