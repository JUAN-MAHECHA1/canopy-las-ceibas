<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * El liderazgo de turno rota entre los guías. Esta clase garantiza la
 * regla de negocio: SOLO puede haber un guía con es_lider = true a la vez.
 */
class LeadershipService
{
    public function liderActual(): ?User
    {
        return User::lideres()->first();
    }

    public function transferirA(User $nuevoLider): void
    {
        if (! $nuevoLider->isGuia()) {
            throw new InvalidArgumentException('El liderazgo solo puede asignarse a un usuario con rol guía.');
        }

        DB::transaction(function () use ($nuevoLider) {
            User::guias()->where('id', '!=', $nuevoLider->id)->update(['es_lider' => false]);
            $nuevoLider->update(['es_lider' => true]);
        });
    }
}
