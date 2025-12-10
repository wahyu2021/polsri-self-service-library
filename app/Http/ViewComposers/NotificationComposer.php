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
        if (!Auth::check() || Auth::user()->role->value !== 'mahasiswa') {
            return;
        }

        // Efficient count query on the notifications table
        $count = Auth::user()->unreadNotifications()->count();

        $view->with('globalNotificationCount', $count);
    }
}
