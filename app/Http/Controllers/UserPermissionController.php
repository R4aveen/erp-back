<?php

namespace App\Http\Controllers;

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
    public function store(Request $req, Usuario $usuario)
    {
        $req->validate(['permiso' => 'required|exists:permisos,clave']);
        $permiso = Permiso::where('clave',$req->permiso)->first();

        $usuario->permisosDirectos()->syncWithoutDetaching($permiso->id);

        return response()->json(['mensaje'=>'Permiso asignado']);
    }

    // REVOCAR permiso
    public function destroy(Usuario $usuario, string $clave)
    {
        $permiso = Permiso::where('clave',$clave)->firstOrFail();
        $usuario->permisosDirectos()->detach($permiso->id);

        return response()->json(['mensaje'=>'Permiso revocado']);
    }
}
