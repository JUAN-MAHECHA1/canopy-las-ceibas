<?php
// Fragmento a copiar dentro de tu bootstrap/app.php (Laravel 11+)
// Registra el alias 'role' usado en routes/web.php: ->middleware('role:admin')

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Configuration\Middleware;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => CheckRole::class,
    ]);
})

// Si usas Laravel 10 (con app/Http/Kernel.php), en su lugar agrega en
// $routeMiddleware o $middlewareAliases:
// 'role' => \App\Http\Middleware\CheckRole::class,
