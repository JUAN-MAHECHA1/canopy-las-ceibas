<?php

namespace App\Http\Controllers\Guia;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function index(): View
    {
        $guia = auth()->user();

        return view('dashboard.guia', [
            'gananciaSemanal' => $this->paymentService->gananciaGuiaSemanaActual($guia),
            'diasTrabajados' => $guia->workDaysComoGuia()
                ->whereBetween('fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
        ]);
    }
}
