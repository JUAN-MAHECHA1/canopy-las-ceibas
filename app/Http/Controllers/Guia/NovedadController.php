<?php

namespace App\Http\Controllers\Guia;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNovedadRequest;
use App\Models\Novedad;
use App\Services\WorkDayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NovedadController extends Controller
{
    public function __construct(private readonly WorkDayService $workDayService)
    {
    }

    public function create(): View
    {
        $novedades = Novedad::where('guia_id', auth()->id())
            ->latest()
            ->limit(20)
            ->get();

        return view('novedades.create', compact('novedades'));
    }

    public function store(StoreNovedadRequest $request): RedirectResponse
    {
        Novedad::create([
            ...$request->validated(),
            'guia_id' => auth()->id(),
            'work_day_id' => $this->workDayService->deHoy()?->id,
        ]);

        return back()->with('success', 'Novedad reportada correctamente.');
    }
}
