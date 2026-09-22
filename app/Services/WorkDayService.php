<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkDay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Gestiona el ciclo de vida del turno diario (work_day): quién lo abre
 * y qué guías quedan activos para recibir pago ese día.
 */
class WorkDayService
{
    /** Retorna el work_day de hoy, o null si nadie lo ha abierto todavía. */
    public function deHoy(): ?WorkDay
    {
        return WorkDay::whereDate('fecha', Carbon::today())->first();
    }

    public function abrir(User $lider, ?Carbon $fecha = null): WorkDay
    {
        $fecha ??= Carbon::today();

        return WorkDay::firstOrCreate(
            ['fecha' => $fecha->toDateString()],
            ['abierto_por' => $lider->id, 'activo' => true]
        );
    }

    /**
     * Reemplaza la lista de guías activos del día (sync).
     * $guiaIds debe contener solo ids de usuarios con role = guia.
     */
    public function asignarGuias(WorkDay $workDay, array $guiaIds): void
    {
        DB::transaction(function () use ($workDay, $guiaIds) {
            $workDay->guiasActivos()->sync($guiaIds);
        });
    }

    public function cerrar(WorkDay $workDay): void
    {
        $workDay->update(['activo' => false, 'cerrado_at' => now()]);
    }
}
