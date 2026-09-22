<?php

namespace App\Http\Controllers\Lider;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkDayRequest;
use App\Http\Requests\TransferLeadershipRequest;
use App\Http\Requests\UpdateWorkDayGuidesRequest;
use App\Models\User;
use App\Services\LeadershipService;
use App\Services\WorkDayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkDayController extends Controller
{
    public function __construct(
        private readonly WorkDayService $workDayService,
        private readonly LeadershipService $leadershipService,
    ) {
    }

    public function index(): View
    {
        $workDay = $this->workDayService->deHoy();
        $guias = User::guias()->orderBy('name')->get();
        $guiaIdsActivos = $workDay ? $workDay->guiasActivos()->pluck('users.id')->all() : [];

        return view('work-days.index', [
            'workDay' => $workDay,
            'guias' => $guias,
            'guiaIdsActivos' => $guiaIdsActivos,
            'liderActual' => $this->leadershipService->liderActual(),
        ]);
    }

    public function store(StoreWorkDayRequest $request): RedirectResponse
    {
        $this->workDayService->abrir(auth()->user());

        return back()->with('success', 'Turno del día abierto correctamente.');
    }

    public function updateGuides(UpdateWorkDayGuidesRequest $request): RedirectResponse
    {
        $workDay = $this->workDayService->deHoy();

        if (! $workDay) {
            return back()->withErrors(['guia_ids' => 'Primero debes abrir el turno del día.']);
        }

        $this->workDayService->asignarGuias($workDay, $request->validated('guia_ids'));

        return back()->with('success', 'Guías activos del día actualizados.');
    }

    /** El liderazgo rota: el líder actual (o un admin) pasa la batuta a otro guía. */
    public function transferLeadership(TransferLeadershipRequest $request): RedirectResponse
    {
        $nuevoLider = User::guias()->findOrFail($request->validated('guia_id'));

        $this->leadershipService->transferirA($nuevoLider);

        return back()->with('success', "{$nuevoLider->name} ahora es el guía líder de turno.");
    }
}
