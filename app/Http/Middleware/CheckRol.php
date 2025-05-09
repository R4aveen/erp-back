<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->rol->nombre, $roles)) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        return $next($request);
    }
}
