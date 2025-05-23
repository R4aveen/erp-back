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
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\FeatureController;

// ─── RUTAS PÚBLICAS ───────────────────────────────────────────────────────────────
Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/login',    [AuthController::class, 'login']);

// ─── RUTAS PROTEGIDAS (JWT) ───────────────────────────────────────────────────────
Route::middleware('auth:api')->group(function () {

    // Perfil y activación obligatoria
    Route::get('/perfil', [ApiAuthController::class, 'profile'])
         ->middleware('verificar.activacion');

    // Refresh de token
    Route::post('/refresh', function () {
        try {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->refresh();
            return response()->json(compact('token'));
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'No se pudo refrescar el token'], 401);
        }
    });

    // Cierre de sesión y activación
    Route::post('/logout',         [AuthController::class, 'logout']);
    Route::post('/activar-cuenta', [AuthController::class, 'activarCuenta']);
    Route::get('/activar',         [AuthController::class, 'verificarTokenActivacion']);

    // Personalización de usuario
    Route::get('/usuario/personalizacion', [AuthController::class, 'obtenerPersonalizacion']);
    Route::put('/usuario/personalizacion', [AuthController::class, 'actualizarPersonalizacion']);

    // ── ENDPOINTS GENERALES ────────────────────────────────────────────────────────

    // Features
    Route::get('/features', [FeatureController::class, 'index']);

    // Usuarios globales
    Route::get('/usuarios', [UsuarioController::class, 'index'])
         ->middleware('permiso:usuario:read');

    // Roles CRUD
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

    // Permisos dinámicos en roles
    Route::get('roles/{rol}/permisos',           [RolPermissionController::class, 'index'])
         ->middleware('permiso:rol:read');
    Route::post('roles/{rol}/permisos',          [RolPermissionController::class, 'store'])
         ->middleware('permiso:rol:assign');
    Route::delete('roles/{rol}/permisos/{clave}',[RolPermissionController::class, 'destroy'])
         ->middleware('permiso:rol:assign');

    // División Político Administrativa (DPA) v1
    Route::prefix('v1')->group(function () {
        Route::get('regiones',                       [DpaController::class, 'regiones']);
        Route::get('regiones/{region}/provincias',   [DpaController::class, 'provincias']);
        Route::get('provincias',                     [DpaController::class, 'provincias']);
        Route::get('provincias/{provincia}/comunas', [DpaController::class, 'comunas']);
        Route::get('comunas',                        [DpaController::class, 'comunas']);
    });

    // ── EMPRESAS Y RELACIONES ───────────────────────────────────────────────────────

    // CRUD de empresas
    Route::get('/empresas',               [EmpresaController::class, 'index']);
    Route::post('/empresas',              [EmpresaController::class, 'store'])
         ->middleware('permiso:empresa:create');
    Route::get('/empresas/{empresa}',     [EmpresaController::class, 'show']);
    Route::put('/empresas/{empresa}',     [EmpresaController::class, 'update'])
         ->middleware('permiso:empresa:update');
    Route::delete('/empresas/{empresa}',  [EmpresaController::class, 'destroy'])
         ->middleware('permiso:empresa:delete');

    // Relaciones de empresa (anidadas)
    Route::prefix('/empresas/{empresa}')
         ->middleware(VerificarAccesoEmpresa::class)
         ->group(function () {
             // Subempresas
             Route::get('/subempresas', [EmpresaController::class, 'subempresas'])
                  ->middleware('permiso:ver_subempresa');
             Route::post('/subempresas',[SubempresaController::class, 'store'])
                  ->middleware('permiso:crear_subempresa');

             // Productos
             Route::post('/productos',             [ProductoController::class, 'store'])
                  ->middleware('permiso:crear_producto');
             Route::put('/productos/{producto}',   [ProductoController::class, 'update'])
                  ->middleware('permiso:editar_producto');
             Route::delete('/productos/{producto}',[ProductoController::class, 'destroy'])
                  ->middleware('permiso:eliminar_producto');

             // Usuarios de la empresa
             Route::get('/usuarios', [EmpresaController::class, 'listarUsuarios'])
                  ->middleware('permiso:ver_usuarios');
             Route::post('/invitar',  [EmpresaController::class, 'invitar'])
                  ->middleware('permiso:invitar_usuario');
    });

    // CRUD de subempresas sueltas
    Route::get('/subempresas',                 [SubempresaController::class, 'index']);
    Route::get('/subempresas/{subempresa}',    [SubempresaController::class, 'show']);
    Route::put('/subempresas/{subempresa}',    [SubempresaController::class, 'update'])
         ->middleware('permiso:subempresa:update');
    Route::delete('/subempresas/{subempresa}', [SubempresaController::class, 'destroy'])
         ->middleware('permiso:subempresa:delete');
    Route::get('/subempresas/{subempresa}/sucursales',
         [SubempresaController::class, 'sucursales']);

    // CRUD de sucursales
    Route::get('/sucursales',                    [SucursalController::class, 'index']);
    Route::post('/subempresas/{subempresa}/sucursales',
         [SucursalController::class, 'store'])
         ->middleware('permiso:sucursal:create');
    Route::get('/sucursales/{sucursal}',         [SucursalController::class, 'show']);
    Route::put('/sucursales/{sucursal}',         [SucursalController::class, 'update'])
         ->middleware('permiso:sucursal:update');
    Route::delete('/sucursales/{sucursal}',      [SucursalController::class, 'destroy'])
         ->middleware('permiso:sucursal:delete');
    Route::get('/sucursales/{sucursal}/usuarios', [SucursalController::class, 'usuarios']);

    // Rutas de permisos de usuario
    Route::prefix('usuarios/{usuario}/permisos')
         ->middleware(['auth:api','permiso:usuario:update'])
         ->group(function () {
             Route::get('/',    [UserPermissionController::class,'index']);
             Route::post('/',   [UserPermissionController::class,'store']);
             Route::delete('/{permiso}', [UserPermissionController::class,'destroy']);
         });

});
