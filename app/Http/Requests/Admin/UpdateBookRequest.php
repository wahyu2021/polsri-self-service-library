<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => ['required', 'string', 'max:20', Rule::unique('books')->ignore($this->book)],
            'author' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}