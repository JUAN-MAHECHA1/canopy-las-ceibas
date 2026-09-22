<?php

namespace App\Http\Controllers\Guia;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistroClienteRequest;
use App\Models\Empresa;
use App\Models\RegistroCliente;
use App\Services\PaymentService;
use App\Services\WorkDayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegistroClienteController extends Controller
{
    public function __construct(
        private readonly WorkDayService $workDayService,
        private readonly PaymentService $paymentService,
    ) {
    }

    public function create(): View
    {
        $workDay = $this->workDayService->deHoy();
        $empresas = Empresa::activas()->get();

        $registrosHoy = $workDay
            ? RegistroCliente::where('work_day_id', $workDay->id)
                ->where('guia_registrador_id', auth()->id())
                ->latest()
                ->get()
            : collect();

        return view('registro-clientes.create', [
            'empresas' => $empresas,
            'workDay' => $workDay,
            'registrosHoy' => $registrosHoy,
            'gananciaSemanal' => $this->paymentService->gananciaGuiaSemanaActual(auth()->user()),
        ]);
    }

    public function store(StoreRegistroClienteRequest $request): RedirectResponse
    {
        $workDay = $this->workDayService->deHoy();

        if (! $workDay || ! $workDay->activo) {
            return back()->withErrors(['empresa_id' => 'El líder de turno aún no ha abierto el día de trabajo.']);
        }

        if (! $workDay->guiasActivos()->where('users.id', auth()->id())->exists()) {
            return back()->withErrors(['empresa_id' => 'No estás marcado como guía activo hoy. Habla con tu líder de turno.']);
        }

        RegistroCliente::create([
            ...$request->validated(),
            'work_day_id' => $workDay->id,
            'guia_registrador_id' => auth()->id(),
            'hora_registro' => now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Cliente registrado correctamente.');
    }
}
