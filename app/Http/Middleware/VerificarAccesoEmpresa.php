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
        $empresaId = $request->route('empresa'); 

        if (!$usuario->empresas()->where('empresas.id', $empresaId)->exists()) {
            return response()->json(['error' => 'Acceso denegado a esta empresa'], 403);
        }

        return $next($request);
    }
}

