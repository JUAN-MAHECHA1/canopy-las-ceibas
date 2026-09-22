<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function index(): View
    {
        return view('dashboard.admin', [
            'pagosSemanales' => $this->paymentService->reportePagosSemanales(),
        ]);
    }
}
