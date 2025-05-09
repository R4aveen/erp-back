<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermiso
{
    public function handle($request, Closure $next, string $permiso)
    {
        if (! Auth::user()?->tienePermiso($permiso)) {
            return response()->json(['error' => 'Sin permiso'], 403);
        }
        return $next($request);
    }
}
