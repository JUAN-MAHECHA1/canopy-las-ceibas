<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NovedadController extends Controller
{
    public function index(Request $request): View
    {
        $novedades = Novedad::with('guia')
            ->tipo($request->query('tipo'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.novedades.index', [
            'novedades' => $novedades,
            'tipoActual' => $request->query('tipo'),
        ]);
    }
}
