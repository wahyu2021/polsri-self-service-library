<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanDueSoonNotification extends Notification
{
    use Queueable;

    protected Loan $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'due_soon',
            'title' => 'Jatuh Tempo Besok',
            'message' => 'Buku "' . $this->loan->book->title . '" harus dikembalikan besok.',
            'loan_id' => $this->loan->id,
            'icon' => '⏳',
            'color' => 'amber'
        ];
    }
}