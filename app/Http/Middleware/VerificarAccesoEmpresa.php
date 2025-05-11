<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerificarAccesoEmpresa
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = JWTAuth::parseToken()->authenticate();

        // Carga explícita de la relación
        $usuario->loadMissing('empresas');

        $empresa = $request->route('empresa');
        $empresaId = $empresa instanceof \App\Models\Empresa ? $empresa->id : $empresa;     

        if (!$usuario->empresas->pluck('id')->contains($empresaId)) {
            return response()->json(['error' => 'Acceso denegado a esta empresa'], 403);
        }

        return $next($request);
    }

}

