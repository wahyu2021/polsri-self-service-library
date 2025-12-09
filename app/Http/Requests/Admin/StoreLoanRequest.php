<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Book;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'book_id' => [
                'required', 
                'exists:books,id',
                function ($attribute, $value, $fail) {
                    $book = Book::find($value);
                    if ($book && $book->stock < 1) {
                        $fail('Stok buku ini sedang kosong.');
                    }
                },
            ],
        ];
    }
}