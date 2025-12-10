<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'isbn' => ['required', 'string', 'exists:books,isbn'],
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.exists' => 'Buku dengan ISBN ini tidak ditemukan di sistem.',
        ];
    }
}
