<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Devuelve todos los usuarios con sus roles y permisos.
     */
    public function index()
    {
        $usuarios = Usuario::with(['roles.permisos', 'permisosDirectos'])->get();

        // Mapear a la forma que tu frontend espera
        $data = $usuarios->map(fn($u) => [
            'id'      => $u->id,
            'nombre'  => $u->nombre,
            'email'   => $u->email,
            'roles'   => $u->roles->map(fn($r) => [
                'id'   => $r->id,
                'slug' => $r->slug,
                'nombre' => $r->nombre,
            ]),
            'permisos'=> $u->obtenerPermisos(),  // método que veremos abajo
        ]);

        return response()->json(['data' => $data]);
    }
}