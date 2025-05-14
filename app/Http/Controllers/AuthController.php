<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Registro de un nuevo usuario y generación de JWT.
     */
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:usuarios',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($usuario);

        return response()->json(compact('usuario', 'token'), 201);
    }

    /**
     * Autenticación y emisión de JWT.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json(compact('token'));
    }

    /**
     * Cierre de sesión (invalidación del token).
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['mensaje' => 'Sesión cerrada correctamente.']);
    }

    /**
     * Verificación de token de activación.
     */
    public function verificarTokenActivacion(Request $request)
    {
        $usuario = Usuario::where('token_activacion', $request->query('token'))->first();

        if (! $usuario) {
            return response()->json(['error' => 'Token inválido o expirado.'], 404);
        }

        return response()->json([
            'mensaje' => 'Token válido.',
            'email'   => $usuario->email,
            'nombre'  => $usuario->nombre,
        ]);
    }

    /**
     * Activación de cuenta tras recibir token.
     */
    public function activarCuenta(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
        if ($user->activado) {
            return response()->json(['mensaje' => 'Cuenta ya activada.'], 400);
        }

        $user->password  = Hash::make($request->password);
        $user->activado  = true;
        $user->save();

        return response()->json(['mensaje' => 'Cuenta activada correctamente.']);
    }

    /**
     * Devuelve perfil con permisos y personalización.
     */
    public function perfil(Request $request)
    {
        /** @var Usuario $user */
        $user = Auth::user()->load(['roles.permisos', 'personalizacion']);

        $permisos = $user->roles
            ->flatMap(fn($rol) => $rol->permisos)
            ->pluck('clave')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'id'              => $user->id,
            'nombre'          => $user->nombre,
            'email'           => $user->email,
            'permisos'        => $permisos,
            'personalizacion' => $user->personalizacion ? [
                'id'                => $user->personalizacion->id,
                'fecha_creacion'    => $user->personalizacion->created_at->toDateTimeString(),
                'fecha_modificacion'=> $user->personalizacion->updated_at->toDateTimeString(),
                'tema'              => $user->personalizacion->tema,
                'font_size'         => $user->personalizacion->font_size,
                'usuario'           => $user->personalizacion->usuario,
                'sucursal_principal'=> $user->personalizacion->sucursal_principal,
                'empresa'           => $user->personalizacion->empresa,
            ] : null,
        ]);
    }

    /**
     * Endpoint independiente para obtener sólo la personalización.
     */
    public function obtenerPersonalizacion(Request $request)
    {
        $user = Auth::user()->load('personalizacion');

        if (! $user->personalizacion) {
            return response()->json(null, 204);
        }

        $p = $user->personalizacion;
        return response()->json([
            'id'                => $p->id,
            'fecha_creacion'    => $p->created_at->toDateTimeString(),
            'fecha_modificacion'=> $p->updated_at->toDateTimeString(),
            'tema'              => $p->tema,
            'font_size'         => $p->font_size,
            'usuario'           => $p->usuario,
            'sucursal_principal'=> $p->sucursal_principal,
            'empresa'           => $p->empresa,
        ]);
    }
    public function actualizarPersonalizacion(Request $request)
    {
        $data = $request->validate([
            'tema'      => ['required', Rule::in(['1','2','3'])],
            'font_size' => 'required|integer|min:8|max:72',
        ]);

        /** @var Usuario $user */
        $user = Auth::user();

        // updateOrCreate: si no existe, lo crea; si existe, lo actualiza
        $personalizacion = $user->personalizacion()
            ->updateOrCreate(
                ['usuario' => $user->id],
                $data
            );

        return response()->json([
            'personalizacion' => [
                'id'                => $personalizacion->id,
                'fecha_creacion'    => $personalizacion->created_at->toDateTimeString(),
                'fecha_modificacion'=> $personalizacion->updated_at->toDateTimeString(),
                'tema'              => $personalizacion->tema,
                'font_size'         => $personalizacion->font_size,
                'usuario'           => $personalizacion->usuario,
                'sucursal_principal'=> $personalizacion->sucursal_principal,
                'empresa'           => $personalizacion->empresa,
            ],
        ]);
    }
}
