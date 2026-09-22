<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNovedadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGuia() ?? false;
    }

    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(['cliente', 'equipo', 'incidente'])],
            'titulo' => ['nullable', 'string', 'max:150'],
            'descripcion' => ['required', 'string', 'max:2000'],
        ];
    }
}
