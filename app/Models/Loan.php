<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\LoanStatus;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'book_id',
        'status',
        'borrow_date',
        'due_date',
        'return_date',
        'fine_amount',
        'is_fine_paid',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'status' => LoanStatus::class,
        'is_fine_paid' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if loan is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === LoanStatus::BORROWED && Carbon::now()->gt($this->due_date);
    }
}
