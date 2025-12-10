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
            'email' => ['required', 'string', 'email', 'lowercase', 'max:255', Rule::unique('users')->ignore($this->user)],
            'nim' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($this->user)],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ];
    }

    // protected function passedValidation()
    // {
    //     if ($this->filled('password')) {
    //         $this->merge(['password' => Hash::make($this->password)]);
    //     } else {
    //         $this->request->remove('password');
    //     }
    // }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        if(filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        return $validated;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',
            'nim.string' => 'NIM harus berupa teks.',
            'nim.max' => 'NIM tidak boleh lebih dari 20 karakter.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'email.string' => 'Alamat email harus berupa teks.',
            'email.lowercase' => 'Alamat email harus huruf kecil semua.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ];
    }
}