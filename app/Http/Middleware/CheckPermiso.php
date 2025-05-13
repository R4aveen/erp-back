<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermiso
{
    public function handle(Request $request, Closure $next, string $clavePermiso)
    {
        $user = Auth::user();

        // 1) Si el usuario tiene super_admin, dejamos pasar TODO
        if ($user && $user->permisos->pluck('clave')->contains('super_admin')) {
            return $next($request);
        }

        // 2) Si no, chequeamos el permiso específico
        if (! $user || ! $user->permisos->pluck('clave')->contains($clavePermiso)) {
            return response()->json(['error' => 'Sin permiso.'], 403);
        }

        return $next($request);
    }


}
