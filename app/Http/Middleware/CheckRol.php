<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRol
{
    public function handle(Request $request, Closure $next, ...$slugs)
    {
        $user = Auth::user();

        // Compara contra slug, no contra nombre
        if (!$user || ! in_array($user->rol->slug, $slugs)) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        return $next($request);
    }

}
// Compare this snippet from app/Models/Usuario.php: