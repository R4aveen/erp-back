<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermiso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @param  string                  ...$permisos  Uno o varios 'clavePermiso'
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$permisos)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'No autenticado.'], 401);
        }

        // 1) Super-admin lo salta todo
        $user->loadMissing('roles');
        if ($user->roles->pluck('slug')->contains('super_admin')) {
            return $next($request);
        }

        // 2) Colección de permisos del usuario (string[])
        $clavesUsuario = $user->permisos();

        // 3) Recorro cada permiso que me hayan pasado por middleware
        foreach ($permisos as $clave) {
            // 3.a) permiso exacto
            if (in_array($clave, $clavesUsuario, true)) {
                return $next($request);
            }

            // 3.b) comodín (recurso:*)
            $parts   = explode(':', $clave, 2);
            $recurso = $parts[0] ?? null;
            if ($recurso && in_array($recurso . ':*', $clavesUsuario, true)) {
                return $next($request);
            }
        }

        // 4) Si no encontró ninguno
        return response()->json(['error' => 'Sin permiso.'], 403);
    }

}
