<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkDay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

/**
 * Centraliza TODO el cálculo de dinero del sistema:
 * - Cuánto genera un día
 * - Cuánto le corresponde a cada guía activo ese día
 * - Reportes por empresa y por semana
 *
 * Regla de negocio: cada persona = valor_por_persona (config).
 * El total del día se divide en partes iguales entre los guías
 * que estuvieron activos ese día (work_day_guides).
 */
class PaymentService
{
    private int $valorPorPersona;

    public function __construct()
    {
        $this->valorPorPersona = (int) config('canopy.valor_por_persona', 10000);
    }

    public function totalPersonasDelDia(WorkDay $workDay): int
    {
        return (int) $workDay->registros()->sum('cantidad_personas');
    }

    public function totalDineroDelDia(WorkDay $workDay): int
    {
        return $this->totalPersonasDelDia($workDay) * $this->valorPorPersona;
    }

    /** @return array{monto_por_guia:int, guias_activos:int, total_dinero:int} */
    public function repartoDelDia(WorkDay $workDay): array
    {
        $guiasActivos = $workDay->guiasActivos()->count();
        $totalDinero = $this->totalDineroDelDia($workDay);

        return [
            'total_dinero'   => $totalDinero,
            'guias_activos'  => $guiasActivos,
            'monto_por_guia' => $guiasActivos > 0 ? intdiv($totalDinero, $guiasActivos) : 0,
        ];
    }

    /** Cuánto ha ganado un guía puntual entre dos fechas (inclusive). */
    public function gananciaGuiaEnRango(User $guia, Carbon $inicio, Carbon $fin): int
    {
        $workDays = $guia->workDaysComoGuia()
            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
            ->get();

        return $workDays->sum(function (WorkDay $workDay) {
            return $this->repartoDelDia($workDay)['monto_por_guia'];
        });
    }

    public function gananciaGuiaSemanaActual(User $guia): int
    {
        return $this->gananciaGuiaEnRango($guia, Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
    }

    /**
     * Reporte para el dashboard del JEFE: cuánto generó cada empresa
     * en un rango de fechas (personas y dinero).
     *
     * @return Collection<int, array{empresa:string,color:?string,total_personas:int,total_dinero:int}>
     */
    public function reportePorEmpresa(Carbon $inicio, Carbon $fin): Collection
    {
        return \App\Models\Empresa::activas()
            ->withSum(['registros as total_personas' => function ($query) use ($inicio, $fin) {
                $query->whereHas('workDay', fn ($q) => $q->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()]));
            }], 'cantidad_personas')
            ->get()
            ->map(function ($empresa) {
                $personas = (int) ($empresa->total_personas ?? 0);

                return [
                    'empresa'        => $empresa->nombre,
                    'color'          => $empresa->color,
                    'total_personas' => $personas,
                    'total_dinero'   => $personas * $this->valorPorPersona,
                ];
            });
    }

    /**
     * Matriz semanal Empresa x Día (L, M, Mi, J, V, S, D) igual a la planilla
     * en Excel que ya manejan, para el dashboard de comparación de jefes.
     *
     * @return array{dias: string[], filas: array, totales_dia: array, total_general: int}
     */
    public function matrizSemanalPorEmpresa(Carbon $inicioSemana): array
    {
        $finSemana = $inicioSemana->copy()->endOfWeek();
        $dias = ['L', 'M', 'Mi', 'J', 'V', 'S', 'D'];
        $periodo = CarbonPeriod::create($inicioSemana->copy()->startOfWeek(), $finSemana);

        $empresas = \App\Models\Empresa::activas()->get();
        $filas = [];
        $totalesDia = array_fill_keys($dias, 0);
        $totalGeneral = 0;

        foreach ($empresas as $empresa) {
            $fila = ['empresa' => $empresa->nombre, 'color' => $empresa->color, 'dias' => [], 'total' => 0];

            foreach ($periodo as $fecha) {
                $letraDia = $dias[$fecha->dayOfWeekIso - 1];

                $personas = (int) \App\Models\RegistroCliente::where('empresa_id', $empresa->id)
                    ->whereHas('workDay', fn ($q) => $q->whereDate('fecha', $fecha->toDateString()))
                    ->sum('cantidad_personas');

                $fila['dias'][$letraDia] = ($fila['dias'][$letraDia] ?? 0) + $personas;
                $fila['total'] += $personas;
                $totalesDia[$letraDia] += $personas;
                $totalGeneral += $personas;
            }

            $filas[] = $fila;
        }

        return [
            'dias' => $dias,
            'filas' => $filas,
            'totales_dia' => $totalesDia,
            'total_general' => $totalGeneral,
        ];
    }

    /** Reporte para el dashboard del ADMIN: pagos totales agrupados por semana. */
    public function reportePagosSemanales(int $semanas = 8): Collection
    {
        $resultado = collect();
        $inicio = Carbon::now()->startOfWeek();

        for ($i = 0; $i < $semanas; $i++) {
            $inicioSemana = $inicio->copy()->subWeeks($i);
            $finSemana = $inicioSemana->copy()->endOfWeek();

            $totalPersonas = (int) \App\Models\RegistroCliente::whereHas(
                'workDay',
                fn ($q) => $q->whereBetween('fecha', [$inicioSemana->toDateString(), $finSemana->toDateString()])
            )->sum('cantidad_personas');

            $resultado->push([
                'semana'         => $inicioSemana->translatedFormat('d M') . ' - ' . $finSemana->translatedFormat('d M Y'),
                'total_personas' => $totalPersonas,
                'total_dinero'   => $totalPersonas * $this->valorPorPersona,
            ]);
        }

        return $resultado;
    }
}
