<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DpaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RolPermissionController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\SubempresaController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\VerificarAccesoEmpresa;
use App\Http\Controllers\Api\AuthController   as ApiAuthController;
use App\Http\Controllers\Api\FeatureController;

// Rutas públicas
Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/login',    [AuthController::class, 'login']);

// Rutas protegidas con JWT
Route::middleware('auth:api')->group(function () {

    // Perfil y activación
    Route::get('/perfil', [ApiAuthController::class, 'profile'])
         ->middleware('verificar.activacion');

    // Refresh token JWT
    Route::post('/refresh', function () {
        try {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->refresh();
            return response()->json(compact('token'));
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'No se pudo refrescar el token'], 401);
        }
    });

    // Features
    Route::get('/features', [FeatureController::class, 'index']);

    // CRUD de roles
    Route::get('roles',               [RolController::class, 'index'])
        ->middleware('permiso:rol:read');
    Route::post('roles',              [RolController::class, 'store'])
        ->middleware('permiso:rol:create');
    Route::get('roles/{rol}',         [RolController::class, 'show'])
        ->middleware('permiso:rol:read');
    Route::patch('roles/{rol}',       [RolController::class, 'update'])
        ->middleware('permiso:rol:update');
    Route::delete('roles/{rol}',      [RolController::class, 'destroy'])
        ->middleware('permiso:rol:delete');

    // Gestión dinámica de permisos en un rol
    Route::get('roles/{rol}/permisos',           [RolPermissionController::class, 'index'])
        ->middleware('permiso:rol:read');
    Route::post('roles/{rol}/permisos',          [RolPermissionController::class, 'store'])
        ->middleware('permiso:rol:assign');
    Route::delete('roles/{rol}/permisos/{clave}',[RolPermissionController::class, 'destroy'])
        ->middleware('permiso:rol:assign');

    // Listar todos los usuarios con sus roles y permisos
    Route::get('usuarios', [UsuarioController::class, 'index'])
         ->middleware('permiso:usuario:read');

    // División Político Administrativa (DPA)
    Route::prefix('v1')->group(function () {
        Route::get('regiones',                       [DpaController::class, 'regiones']);
        Route::get('regiones/{region}/provincias',   [DpaController::class, 'provincias']);
        Route::get('provincias',                     [DpaController::class, 'provincias']);
        Route::get('provincias/{provincia}/comunas', [DpaController::class, 'comunas']);
        Route::get('comunas',                        [DpaController::class, 'comunas']);
    });

    // --- EMPRESAS CRUD ---
    Route::get('/empresas',                [EmpresaController::class, 'index']);
    Route::post('/empresas',               [EmpresaController::class, 'store'])
         ->middleware('permiso:empresa:create');
    Route::get('/empresas/{empresa}',      [EmpresaController::class, 'show']);
    Route::put('/empresas/{empresa}',      [EmpresaController::class, 'update'])
         ->middleware('permiso:empresa:update');
    Route::delete('/empresas/{empresa}',   [EmpresaController::class, 'destroy'])
         ->middleware('permiso:empresa:delete');

    // Jerarquía de empresa
    Route::get('/empresas/{empresa}/subempresas', [EmpresaController::class, 'subempresas']);

    // --- SUBEMPRESAS CRUD ---
    Route::get('/subempresas',                    [SubempresaController::class, 'index']);
    Route::post('/empresas/{empresa}/subempresas',[SubempresaController::class, 'store'])
         ->middleware('permiso:subempresa:create');
    Route::get('/subempresas/{subempresa}',       [SubempresaController::class, 'show']);
    Route::put('/subempresas/{subempresa}',       [SubempresaController::class, 'update'])
         ->middleware('permiso:subempresa:update');
    Route::delete('/subempresas/{subempresa}',    [SubempresaController::class, 'destroy'])
         ->middleware('permiso:subempresa:delete');
    Route::get('/subempresas/{subempresa}/sucursales',
         [SubempresaController::class, 'sucursales']);

    // --- SUCURSALES CRUD ---
    Route::get('/sucursales',                     [SucursalController::class, 'index']);
    Route::post('/subempresas/{subempresa}/sucursales',
         [SucursalController::class, 'store'])
         ->middleware('permiso:sucursal:create');
    Route::get('/sucursales/{sucursal}',          [SucursalController::class, 'show']);
    Route::put('/sucursales/{sucursal}',          [SucursalController::class, 'update'])
         ->middleware('permiso:sucursal:update');
    Route::delete('/sucursales/{sucursal}',       [SucursalController::class, 'destroy'])
         ->middleware('permiso:sucursal:delete');
    Route::get('/sucursales/{sucursal}/usuarios', [SucursalController::class, 'usuarios']);

    // Personalización del usuario
    Route::get('/usuario/personalizacion',   [AuthController::class, 'obtenerPersonalizacion']);
    Route::put('/usuario/personalizacion',   [AuthController::class, 'actualizarPersonalizacion']);

    // Activación y logout
    Route::post('/activar-cuenta', [AuthController::class, 'activarCuenta']);
    Route::get('/activar',         [AuthController::class, 'verificarTokenActivacion']);
    Route::post('/logout',         [AuthController::class, 'logout']);

    // --- OPERACIONES POR EMPRESA ---
    Route::prefix('/empresas/{empresa}')
         ->middleware(VerificarAccesoEmpresa::class)
         ->group(function () {

        // SUBEMPRESAS
        Route::post('/subempresas', [SubempresaController::class, 'store'])
             ->middleware('permiso:crear_subempresa');

        // PRODUCTOS
        Route::post('/productos',               [ProductoController::class, 'store'])
             ->middleware('permiso:crear_producto');
        Route::put('/productos/{producto}',      [ProductoController::class, 'update'])
             ->middleware('permiso:editar_producto');
        Route::delete('/productos/{producto}',   [ProductoController::class, 'destroy'])
             ->middleware('permiso:eliminar_producto');

        // USUARIOS
        Route::get('/usuarios', [EmpresaController::class, 'listarUsuarios'])
             ->middleware('permiso:ver_usuarios');
        Route::post('/invitar',  [EmpresaController::class, 'invitar'])
             ->middleware('permiso:invitar_usuario');
    });


    // Rutas de permisos de usuario
    Route::prefix('usuarios/{usuario}/permisos')
         ->middleware(['auth:api','permiso:usuario:update'])
         ->group(function(){
             Route::get('/',          [UserPermissionController::class,'index']);
             Route::post('/',         [UserPermissionController::class,'store']);
             Route::delete('/{permiso}', [UserPermissionController::class,'destroy']);
         });

});
