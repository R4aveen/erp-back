<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Usuario;
use App\Mail\InvitacionUsuario;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
class EmpresaController extends Controller
{
    public function index()
    {
        return Empresa::all();
    }

    public function store(StoreEmpresaRequest $request)
    {
        $data = $request->validated();
        $empresa = Empresa::create($data);
        return response()->json($empresa, 201);
    }

    public function show(Empresa $empresa)
    {
        return $empresa->load('subempresas.sucursales');
    }

    public function update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        $data = $request->validated();
        $empresa->update($data);
        return response()->json($empresa);
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return response()->json(['mensaje' => 'Empresa eliminada.']);
    }

    // ya tenías estos:
    public function subempresas(Empresa $empresa)
    {
        return response()->json($empresa->load('subempresas.sucursales'));
    }

    public function listarUsuarios(Empresa $empresa)
    {
        return response()->json($empresa->load('subempresas.sucursales.usuarios'));
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
        
    public function principal(Request $request)
    {
        $usuario = $request->user();

        // Usamos la relación que apunta al pivote correcto:
        $empresa = $usuario
            ->empresasRoles()                 // en lugar de ->empresas()
            ->with('subempresas.sucursales')
            ->first();

        return response()->json($empresa);
    }

}
