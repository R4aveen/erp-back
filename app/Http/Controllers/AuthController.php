<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string|min:6',
        ]);        

        $token = JWTAuth::fromUser($usuario);
        return response()->json(compact('usuario', 'token'),201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        $credenciales = $request->only('email', 'password');
        
        if (!$token = JWTAuth::attempt($credenciales)) {
            return response()->json(['error' => 'Credenciales invalidas'], 401);
        }
        return response()->json(compact('token'));
    }

    public function activarCuenta(Request $req)
    {
        $req->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
        if ($user->activado) {
            return response()->json(['mensaje' => 'Cuenta ya activada.'], 400);
        }

        $user->password = bcrypt($req->password);
        $user->activado = true;
        $user->save();

        return response()->json(['mensaje' => 'Cuenta activada correctamente.']);
    }
    public function verificarTokenActivacion(Request $request)
    {
        $usuario = Usuario::where('token_activacion', $request->query('token'))->first();

        if (!$usuario) {
            return response()->json(['error' => 'Token inválido o expirado.'], 404);
        }

        return response()->json([
            'mensaje' => 'Token válido.',
            'email' => $usuario->email,
            'nombre' => $usuario->nombre
        ]);
    }



    public function perfil()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['mensaje' => 'Sesión cerrada']);
    }
}
