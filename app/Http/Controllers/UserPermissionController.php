<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPermissionRequest;
use App\Models\Usuario;
use App\Models\Permiso;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    // LISTAR permisos asignados y disponibles
    public function index(Usuario $usuario)
    {
        $asignados  = $usuario->permisosDirectos()->pluck('clave');
        $disponibles = Permiso::pluck('clave');

        return response()->json(compact('asignados','disponibles'));
    }

    // ASIGNAR permiso
    public function store(StoreUserPermissionRequest $request, Usuario $usuario)
    {
        $data = $request->validated();              // ['permiso' => 'clave_x']
        $permiso = Permiso::where('clave', $data['permiso'])->firstOrFail();
        $usuario->permisosDirectos()->syncWithoutDetaching($permiso->id);
        return response()->json(['mensaje' => 'Permiso asignado'], 201);
    }

    // REVOCAR permiso
    public function destroy(Usuario $usuario, string $clave)
    {
        $permiso = Permiso::where('clave',$clave)->firstOrFail();
        $usuario->permisosDirectos()->detach($permiso->id);

        return response()->json(['mensaje'=>'Permiso revocado']);
    }
}
