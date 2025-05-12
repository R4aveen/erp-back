<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificarActivacion
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->activado) {
            return response()->json(['error' => 'Debes activar tu cuenta cambiando tu contraseña.'], 403);
        }

        return $next($request);
    }

}
