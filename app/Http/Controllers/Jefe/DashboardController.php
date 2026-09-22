<?php

namespace App\Http\Controllers\Jefe;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function index(Request $request): View
    {
        $inicioSemana = $request->filled('semana')
            ? Carbon::parse($request->input('semana'))->startOfWeek()
            : Carbon::now()->startOfWeek();

        return view('dashboard.jefe', [
            'matriz' => $this->paymentService->matrizSemanalPorEmpresa($inicioSemana),
            'inicioSemana' => $inicioSemana,
            'reportePorEmpresa' => $this->paymentService->reportePorEmpresa($inicioSemana, $inicioSemana->copy()->endOfWeek()),
        ]);
    }
}
