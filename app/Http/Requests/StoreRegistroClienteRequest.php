<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use App\Rules\HorarioPermitidoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistroClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGuia() ?? false;
    }

    public function rules(): array
    {
        $empresa = Empresa::find($this->input('empresa_id'));

        return [
            'empresa_id' => ['required', 'exists:empresas,id', new HorarioPermitidoRule()],
            'cantidad_personas' => ['required', 'integer', 'min:1', 'max:50'],
            'cedula' => ['nullable', 'string', 'max:20'],

            // Campo condicional según cómo registre la empresa seleccionada
            'nombre_cliente' => [
                Rule::requiredIf(fn () => $empresa && $empresa->usaNombre()),
                'nullable', 'string', 'max:150',
            ],
            'codigo_reserva' => [
                Rule::requiredIf(fn () => $empresa && $empresa->usaCodigo()),
                'nullable', 'string', 'max:50',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_cliente.required' => 'Esta empresa registra por nombre y apellido del cliente.',
            'codigo_reserva.required' => 'Esta empresa registra por código de reserva (ej: R37-27).',
        ];
    }
}
