<?php

namespace App\Rules;

use App\Services\ScheduleValidationService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HorarioPermitidoRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $service = app(ScheduleValidationService::class);

        if (! $service->puedeRegistrarAhora()) {
            $fail($service->mensajeError());
        }
    }
}
