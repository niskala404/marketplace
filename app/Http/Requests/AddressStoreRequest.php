<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'label' => trim((string) $this->input('label')),
            'recipient_name' => trim((string) $this->input('recipient_name')),
            'phone' => preg_replace('/\s+/', '', (string) $this->input('phone')),
            'province' => trim((string) $this->input('province')),
            'city' => trim((string) $this->input('city')),
            'district' => trim((string) $this->input('district')),
            'village' => trim((string) $this->input('village')),
            'postal_code' => trim((string) $this->input('postal_code')),
            'full_address' => trim(strip_tags((string) $this->input('full_address'))),
            'detail_address' => trim(strip_tags((string) $this->input('detail_address'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:60'],
            'recipient_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'province' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'district' => ['required', 'string', 'max:120'],
            'village' => ['required', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'rajaongkir_city_id' => ['nullable', 'integer', 'min:1'],
            'full_address' => ['required', 'string', 'max:1000'],
            'detail_address' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'province.required' => 'Silakan pilih provinsi.',
            'city.required' => 'Silakan pilih kota/kabupaten.',
            'district.required' => 'Silakan pilih kecamatan.',
            'village.required' => 'Silakan pilih kelurahan.',
            'full_address.required' => 'Alamat utama wajib diisi.',
            'latitude.required' => 'Silakan pilih titik lokasi pada peta.',
            'longitude.required' => 'Silakan pilih titik lokasi pada peta.',
        ];
    }
}
