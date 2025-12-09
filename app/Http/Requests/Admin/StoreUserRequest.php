<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Admin only logic is handled by middleware
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nim' => ['nullable', 'string', 'max:20', 'unique:users'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }
}