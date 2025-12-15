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
            'borrow_date' => ['required', 'date_format:Y-m-d'],
            'due_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:borrow_date'],
            'return_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:borrow_date'],
            'fine_amount' => ['nullable', 'numeric', 'min:0'],
            'is_fine_paid' => ['nullable', 'boolean'],
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
            'is_fine_paid' => 'Status Pembayaran Denda',
        ];
    }

    public function messages()
    {
        return [
            'borrow_date.date_format' => 'Format tanggal pinjam tidak valid. Gunakan format YYYY-MM-DD.',
            'due_date.date_format' => 'Format jatuh tempo tidak valid. Gunakan format YYYY-MM-DD.',
            'due_date.after_or_equal' => 'Jatuh tempo harus sama dengan atau setelah tanggal pinjam.',
            'return_date.date_format' => 'Format tanggal kembali tidak valid. Gunakan format YYYY-MM-DD.',
            'return_date.after_or_equal' => 'Tanggal kembali harus sama dengan atau setelah tanggal pinjam.',
        ];
    }
}
