<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FineUnpaidNotification extends Notification
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
            'type' => 'fine',
            'title' => 'Denda Belum Dibayar',
            'message' => 'Anda memiliki tagihan denda Rp' . number_format($this->loan->fine_amount, 0, ',', '.') . ' untuk buku "' . $this->loan->book->title . '".',
            'loan_id' => $this->loan->id,
            'icon' => '💸',
            'color' => 'rose'
        ];
    }
}