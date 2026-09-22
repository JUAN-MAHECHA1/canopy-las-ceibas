<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Uso en rutas: ->middleware('role:admin') / ->middleware('role:jefe,admin')
 * El valor especial "lider" no es un role real: se cumple si el usuario
 * es guía Y tiene es_lider = true (ver User::isLider()).
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        foreach ($roles as $rolPermitido) {
            if ($rolPermitido === 'lider' && $user->isLider()) {
                return $next($request);
            }

            if ($user->role === $rolPermitido) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}
