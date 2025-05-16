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

        // 1) Super-admin salta todo
        $user->loadMissing('roles');
        if ($user->roles->pluck('slug')->contains('super_admin')) {
            return $next($request);
        }

        // 2) Obtengo el array combinado
        $clavesUsuario = $user->obtenerPermisos();

        // 3) Compruebo cada permiso recibido
        foreach ($permisos as $clave) {
            if (in_array($clave, $clavesUsuario, true)) {
                return $next($request);
            }
            [$recurso] = explode(':', $clave, 2);
            if ($recurso && in_array("$recurso:*", $clavesUsuario, true)) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Sin permiso.'], 403);
    }

}
