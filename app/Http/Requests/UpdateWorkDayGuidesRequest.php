<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkDayGuidesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isLider() ?? false;
    }

    public function rules(): array
    {
        return [
            'guia_ids' => ['required', 'array', 'min:1'],
            'guia_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}
