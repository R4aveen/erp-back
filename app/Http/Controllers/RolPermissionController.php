<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRolPermissionRequest;
use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Http\JsonResponse;

class RolPermissionController extends Controller
{
    public function index(Rol $rol): JsonResponse
    {
        return response()->json($rol->permisos()->get());
    }

    public function store(StoreRolPermissionRequest $request, Rol $rol): JsonResponse
    {
        $permiso = Permiso::where('clave', $request->permiso)->firstOrFail();
        $rol->permisos()->syncWithoutDetaching($permiso->id);
        return response()->json(['message' => 'Permiso asignado']);
    }

    public function destroy(Rol $rol, string $clave): JsonResponse
    {
        $permiso = Permiso::where('clave', $clave)->firstOrFail();
        $rol->permisos()->detach($permiso->id);
        return response()->json(['message' => 'Permiso removido']);
    }
}