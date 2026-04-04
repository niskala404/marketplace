<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiveStartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'seller';
    }

    public function rules(): array
    {
        return [
            'live_id' => ['nullable', 'integer', 'exists:live_streams,id'],
            'title' => ['required_without:live_id', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:2000'],
            'stream_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}

