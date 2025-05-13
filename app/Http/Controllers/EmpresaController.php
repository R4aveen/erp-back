<?php

namespace App\Http\Controllers;

use App\Mail\InvitacionUsuario;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Sucursal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmpresaController extends Controller
{
    // Lista todas las empresas (solo para admins o gerentes globales)
    public function index()
    {
        return Empresa::all();
    }

    // Devuelve una empresa con sus subempresas y sucursales (GET /empresas/{id}/subempresas)
    public function subempresas($id)
    {
        $empresa = Empresa::with('subempresas.sucursales')->findOrFail($id);
        return response()->json($empresa);
    }

    // Devuelve una empresa con todas sus subempresas, sucursales y usuarios (GET /empresa/{id}/usuarios)
    public function listarUsuarios($id)
    {
        $empresa = Empresa::with('subempresas.sucursales.usuarios')->findOrFail($id);
        return response()->json($empresa);
    }

    public function invitar(Request $request, $empresaId)
    {
        $validator = Validator::make($request->all(), [
            'nombre'       => 'required|string|max:255',
            'email'        => 'required|email|unique:usuarios,email',
            'rol_id'       => 'required|exists:roles,id',
            'sucursal_id'  => 'nullable|exists:sucursales,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $empresa = Empresa::findOrFail($empresaId);

        // Crear usuario provisional
        $passwordTemporal = Str::random(10);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($passwordTemporal),
            'activado' => false,
        ]);

        // Asociar empresa y rol
        $usuario->empresas()->attach($empresa->id, ['rol_id' => $request->rol_id]);

        // Asociar sucursal si viene
        if ($request->filled('sucursal_id')) {
            $usuario->sucursales()->attach($request->sucursal_id);
        }

        // Generar y guardar token de activación + notificar
        $token = Str::uuid();
        $usuario->token_activacion = $token;
        $usuario->save();
        Mail::to($usuario->email)->send(new InvitacionUsuario($usuario, $token));


        return response()->json([
            'mensaje' => 'Usuario invitado correctamente.',
            'usuario' => $usuario,
            'password_temporal' => $passwordTemporal
        ], 201);
    }

    
}
