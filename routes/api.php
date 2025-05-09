<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\SubempresaController;
use App\Http\Middleware\VerificarAccesoEmpresa;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por autenticación JWT
Route::middleware('auth:api')->group(function () {
    // Perfil y logout
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas de jerarquía empresarial
    Route::get('/empresas', [EmpresaController::class, 'index']);
    Route::get('/empresas/{empresa}/subempresas', [EmpresaController::class, 'subempresas']);
    Route::get('/subempresas/{subempresa}/sucursales', [SubempresaController::class, 'sucursales']);
    Route::get('/sucursales/{sucursal}/usuarios', [SucursalController::class, 'usuarios']);

    // Refresh de token (mantener fuera de los GET/POST generales)
    Route::post('/refresh', function () {
        try {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->refresh();
            return response()->json(compact('token'));
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'No se pudo refrescar el token'], 401);
        }
    });
});

// Rutas protegidas por middleware adicional de acceso a empresa
Route::middleware([VerificarAccesoEmpresa::class])->group(function () {
    Route::get('/empresa/{empresa}/usuarios', [EmpresaController::class, 'listarUsuarios']);
    Route::post('/empresa/{empresa}/producto', [ProductoController::class, 'crear']);
});
