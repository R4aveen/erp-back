<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use App\Models\Rol;
use Illuminate\Http\JsonResponse;

class RolController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Rol::with('permisos')->get();
        return response()->json($roles);
    }

    public function store(StoreRolRequest $request): JsonResponse
    {
        $rol = Rol::create($request->validated());
        return response()->json($rol, 201);
    }

    public function show(Rol $rol): JsonResponse
    {
        $rol->load('permisos');
        return response()->json($rol);
    }

    public function update(UpdateRolRequest $request, Rol $rol): JsonResponse
    {
        $rol->update($request->validated());
        return response()->json($rol);
    }

    public function destroy(Rol $rol): JsonResponse
    {
        $rol->delete();
        return response()->json(['message' => 'Rol eliminado']);
    }
}