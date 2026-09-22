<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkDayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isLider() ?? false;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['nullable', 'date'],
        ];
    }
}
