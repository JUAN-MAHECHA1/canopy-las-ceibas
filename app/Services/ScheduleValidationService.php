<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * Responsable únicamente de decidir si, en este momento, se permite
 * registrar un cliente. Entre semana no hay restricción; sábados,
 * domingos y festivos solo se permite entre las horas configuradas.
 */
class ScheduleValidationService
{
    public function puedeRegistrarAhora(): bool
    {
        return $this->puedeRegistrarEn(Carbon::now());
    }

    public function puedeRegistrarEn(Carbon $momento): bool
    {
        if (! $this->esDiaRestringido($momento)) {
            return true;
        }

        $inicio = Carbon::parse($momento->toDateString() . ' ' . config('canopy.horario_restringido.inicio'));
        $fin    = Carbon::parse($momento->toDateString() . ' ' . config('canopy.horario_restringido.fin'));

        return $momento->between($inicio, $fin);
    }

    public function esDiaRestringido(Carbon $momento): bool
    {
        return $momento->isWeekend() || $this->esFestivo($momento);
    }

    public function esFestivo(Carbon $momento): bool
    {
        return in_array($momento->toDateString(), config('canopy.festivos', []), true);
    }

    public function mensajeError(): string
    {
        return sprintf(
            'Los fines de semana y festivos solo se pueden registrar clientes entre las %s y las %s.',
            config('canopy.horario_restringido.inicio'),
            config('canopy.horario_restringido.fin')
        );
    }
}
