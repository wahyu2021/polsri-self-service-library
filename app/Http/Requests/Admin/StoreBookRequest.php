<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn|max:20',
            'author' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}