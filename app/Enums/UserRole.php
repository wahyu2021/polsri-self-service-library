<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MAHASISWA = 'mahasiswa';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::MAHASISWA => 'Mahasiswa',
        };
    }
}