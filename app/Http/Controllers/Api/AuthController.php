<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function profile(Request $request)
    {
        $user = Auth::user()->load('roles.permisos','roles.features');

        $permisos = $user->roles
            ->flatMap(fn($r) => $r->permisos)
            ->pluck('clave')
            ->unique()
            ->values()
            ->toArray();

        $features = $user->roles
            ->flatMap(fn($r) => $r->features)
            ->pluck('clave')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'user'     => $user->only(['id','nombre','email']),
            'permisos' => $permisos,
            'features' => $features,
        ]);
    }
}
