<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use App\Enums\LoanStatus;
use App\Notifications\LoanDueSoonNotification;
use App\Notifications\LoanOverdueNotification;
use Carbon\Carbon;

class CheckLoanNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:check-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for due soon and overdue loans and send notifications.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting notification check...');

        // 1. Check Due Soon (Tomorrow)
        $tomorrow = Carbon::tomorrow();
        $dueSoonLoans = Loan::with('user', 'book')
            ->where('status', LoanStatus::BORROWED)
            ->whereDate('due_date', $tomorrow)
            ->get();

        foreach ($dueSoonLoans as $loan) {
            // Prevent duplicate notifications if run multiple times
            // Usually we check if notification already exists for this loan & type today
            // But for simplicity in this MVP, we assume scheduler runs once daily.
            
            $loan->user->notify(new LoanDueSoonNotification($loan));
            $this->info("Sent Due Soon to User ID: {$loan->user_id}");
        }

        // 2. Check Overdue (Today or Past)
        // We might want to notify them every day they are late, or just once. 
        // Let's notify them every day for urgency.
        $overdueLoans = Loan::with('user', 'book')
            ->where('status', LoanStatus::BORROWED)
            ->whereDate('due_date', '<', Carbon::today())
            ->get();

        foreach ($overdueLoans as $loan) {
            $loan->user->notify(new LoanOverdueNotification($loan));
            $this->info("Sent Overdue to User ID: {$loan->user_id}");
        }

        $this->info('Notification check complete.');
    }
}