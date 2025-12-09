<?php

namespace App\Enums;

enum LoanStatus: string
{
    case PENDING_VALIDATION = 'pending_validation';
    case BORROWED = 'borrowed';
    case RETURNED = 'returned';

    public function label(): string
    {
        return match($this) {
            self::PENDING_VALIDATION => 'Menunggu Validasi',
            self::BORROWED => 'Dipinjam',
            self::RETURNED => 'Dikembalikan',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::PENDING_VALIDATION => 'amber',
            self::BORROWED => 'emerald',
            self::RETURNED => 'slate',
        };
    }
}
