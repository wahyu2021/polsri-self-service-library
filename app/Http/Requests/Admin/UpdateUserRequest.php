<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'nim' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($this->user)],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ];
    }

    protected function passedValidation()
    {
        if ($this->filled('password')) {
            $this->merge(['password' => Hash::make($this->password)]);
        } else {
            $this->request->remove('password');
        }
    }
}