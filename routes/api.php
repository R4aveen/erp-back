<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\SubempresaController;
use App\Http\Middleware\VerificarAccesoEmpresa;

// Rutas públicas
Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con JWT
Route::middleware('auth:api')->group(function () {

    // Usuario autenticado
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Jerarquía de empresa
    Route::get('/empresas', [EmpresaController::class, 'index']);
    Route::get('/empresas/{empresa}/subempresas', [EmpresaController::class, 'subempresas']);
    Route::get('/subempresas/{subempresa}/sucursales', [SubempresaController::class, 'sucursales']);
    Route::get('/sucursales/{sucursal}/usuarios', [SucursalController::class, 'usuarios']);

    // --- OPERACIONES POR EMPRESA ----------------
    Route::prefix('/empresas/{empresa}')
          ->middleware(VerificarAccesoEmpresa::class)
          ->group(function () {

        // SUBEMPRESAS
        Route::post('/subempresas',
             [SubempresaController::class, 'store']
        )->middleware('permiso:crear_subempresa');

        // PRODUCTOS
        Route::post('/productos',
             [ProductoController::class, 'store']
        )->middleware('permiso:crear_producto');
        Route::put('/productos/{producto}',
             [ProductoController::class, 'update']
        )->middleware('permiso:editar_producto');
        Route::delete('/productos/{producto}',
             [ProductoController::class, 'destroy']
        )->middleware('permiso:eliminar_producto');

        // USUARIOS
        Route::get('/usuarios',
             [EmpresaController::class, 'listarUsuarios']
        )->middleware('permiso:ver_usuarios');

        Route::post('/invitar',
             [EmpresaController::class, 'invitar']
        )->middleware('permiso:invitar_usuario');
    });

    // --- OPERACIONES POR SUBEMPRESA -------------
    Route::prefix('/subempresas/{subempresa}')
          ->middleware('permiso:crear_sucursal')
          ->group(function () {
        Route::post('/sucursales',
             [SucursalController::class, 'store']);
    });
    // Crear, editar, eliminar producto
    Route::prefix('/empresas/{empresa}')->middleware(VerificarAccesoEmpresa::class)->group(function () {

        Route::post('/productos',  [ProductoController::class, 'store'  ])->middleware('permiso:crear_producto');
        Route::put ('/productos/{producto}', [ProductoController::class, 'update'])->middleware('permiso:editar_producto');
        Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->middleware('permiso:eliminar_producto');

        Route::get('/usuarios',  [EmpresaController::class, 'listarUsuarios'])->middleware('permiso:ver_usuarios');
        Route::post('/invitar',  [EmpresaController::class, 'invitar'      ])->middleware('permiso:invitar_usuario');
    });

    // Refresh token JWT
    Route::post('/refresh', function () {
        try {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->refresh();
            return response()->json(compact('token'));
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'No se pudo refrescar el token'], 401);
        }
    });

    // Acceso específico a una empresa con middleware personalizado
    Route::prefix('/empresa/{empresa}')->middleware(VerificarAccesoEmpresa::class)->group(function () {
        Route::get('/usuarios', [EmpresaController::class, 'listarUsuarios']);
        Route::post('/producto', [ProductoController::class, 'crear']);
    });
});
