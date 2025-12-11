<?php

namespace App\Http\Requests\Admin;

use App\Enums\LoanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(LoanStatus::class)],
            'borrow_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:borrow_date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:borrow_date'],
            'fine_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes()
    {
        return [
            'status' => 'Status Peminjaman',
            'borrow_date' => 'Tanggal Pinjam',
            'due_date' => 'Jatuh Tempo',
            'return_date' => 'Tanggal Kembali',
            'fine_amount' => 'Denda',
        ];
    }
}