<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProductoController;
use App\Http\Middleware\VerificarAccesoEmpresa;
use Illuminate\Support\Facades\Route;

Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class,'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->post('/refresh', function () {
    try {
        $token = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->refresh();
        return response()->json(compact('token'));
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'No se pudo refrescar el token'], 401);
    }
});

Route::middleware([VerificarAccesoEmpresa::class])->group(function () {
    Route::get('/empresa/{empresa}/usuarios', [EmpresaController::class, 'listarUsuarios']);
    Route::post('/empresa/{empresa}/producto', [ProductoController::class, 'crear']);
});