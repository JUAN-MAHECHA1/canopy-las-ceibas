<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuideController as AdminGuideController;
use App\Http\Controllers\Admin\NovedadController as AdminNovedadController;
use App\Http\Controllers\Guia\DashboardController as GuiaDashboardController;
use App\Http\Controllers\Guia\NovedadController as GuiaNovedadController;
use App\Http\Controllers\Guia\RegistroClienteController;
use App\Http\Controllers\Jefe\DashboardController as JefeDashboardController;
use App\Http\Controllers\Lider\WorkDayController;
use Illuminate\Support\Facades\Route;

// Laravel Breeze/UI genera estas: /login, /register, /logout, etc.
require __DIR__.'/auth.php';

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // Redirección genérica al dashboard correcto según el rol del usuario
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match (true) {
            $user->isAdmin() => redirect()->route('admin.dashboard'),
            $user->isJefe()  => redirect()->route('jefe.dashboard'),
            default          => redirect()->route('guia.dashboard'),
        };
    })->name('dashboard');

    // ---------- ADMIN ----------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/guias', [AdminGuideController::class, 'index'])->name('guides.index');
        Route::put('/guias/lider', [AdminGuideController::class, 'setLider'])->name('guides.set-lider');

        Route::get('/novedades', [AdminNovedadController::class, 'index'])->name('novedades.index');
    });

    // ---------- JEFE ----------
    Route::middleware('role:jefe,admin')->prefix('jefe')->name('jefe.')->group(function () {
        Route::get('/dashboard', [JefeDashboardController::class, 'index'])->name('dashboard');
    });

    // ---------- GUIA ----------
    Route::middleware('role:guia')->prefix('guia')->name('guia.')->group(function () {
        Route::get('/dashboard', [GuiaDashboardController::class, 'index'])->name('dashboard');

        Route::get('/registro-clientes', [RegistroClienteController::class, 'create'])->name('registro-clientes.create');
        Route::post('/registro-clientes', [RegistroClienteController::class, 'store'])->name('registro-clientes.store');

        Route::get('/novedades', [GuiaNovedadController::class, 'create'])->name('novedades.create');
        Route::post('/novedades', [GuiaNovedadController::class, 'store'])->name('novedades.store');
    });

    // ---------- LIDER (guía con es_lider = true; el liderazgo rota) ----------
    Route::middleware('role:lider')->prefix('lider')->name('lider.')->group(function () {
        Route::get('/turno', [WorkDayController::class, 'index'])->name('work-days.index');
        Route::post('/turno', [WorkDayController::class, 'store'])->name('work-days.store');
        Route::put('/turno/guias', [WorkDayController::class, 'updateGuides'])->name('work-days.guides.update');
        Route::put('/turno/liderazgo', [WorkDayController::class, 'transferLeadership'])->name('work-days.leadership.transfer');
    });
});
