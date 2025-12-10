<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qr_code' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'qr_code.required' => 'QR Code wajib discan.',
            'latitude.required' => 'Lokasi GPS wajib aktif.',
            'longitude.required' => 'Lokasi GPS wajib aktif.',
        ];
    }
}
