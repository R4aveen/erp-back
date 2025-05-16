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

// Rutas públicas
Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con JWT
Route::middleware('auth:api')->group(function () {

        // CRUD de roles
    Route::get('roles',      [RolController::class, 'index'])->middleware('checkPermiso:rol:read');
    Route::post('roles',     [RolController::class, 'store'])->middleware('checkPermiso:rol:create');
    Route::get('roles/{rol}',[RolController::class, 'show'])->middleware('checkPermiso:rol:read');
    Route::patch('roles/{rol}',[RolController::class, 'update'])->middleware('checkPermiso:rol:update');
    Route::delete('roles/{rol}',[RolController::class, 'destroy'])->middleware('checkPermiso:rol:delete');

    // Gestión dinámica de permisos en un rol
    Route::get('roles/{rol}/permisos',          [RolPermissionController::class, 'index'])->middleware('checkPermiso:rol:read');
    Route::post('roles/{rol}/permisos',         [RolPermissionController::class, 'store'])->middleware('checkPermiso:rol:assign');
    Route::delete('roles/{rol}/permisos/{clave}',[RolPermissionController::class, 'destroy'])->middleware('checkPermiso:rol:assign');
    
    // Listar todos los usuarios con sus roles y permisos
    Route::get('usuarios', [UsuarioController::class, 'index'])->middleware('permiso:usuario:read');

    Route::get('usuarios',[UsuarioController::class, 'index'])->middleware('permiso:usuario:read');

    Route::prefix()->group(function () {
        Route::get('regiones',                 [DpaController::class, 'regiones']);
        Route::get('regiones/{region}/provincias', [DpaController::class, 'provincias']);
        Route::get('provincias',               [DpaController::class, 'provincias']);
        Route::get('provincias/{provincia}/comunas',  [DpaController::class, 'comunas']);
        Route::get('comunas',                  [DpaController::class, 'comunas']);
    });

        // --- EMPRESAS CRUD ---
    Route::middleware('auth:api')->get('/empresa', [EmpresaController::class, 'principal']);    
    Route::get   ('/empresas',                    [EmpresaController::class, 'index']);
    Route::post  ('/empresas',                    [EmpresaController::class, 'store'])->middleware('permiso:empresa:create');
    Route::get   ('/empresas/{empresa}',         [EmpresaController::class, 'show']);
    Route::put   ('/empresas/{empresa}',         [EmpresaController::class, 'update'])->middleware('permiso:empresa:update');
    Route::delete('/empresas/{empresa}',         [EmpresaController::class, 'destroy'])->middleware('permiso:empresa:delete');

    // Jerarquía anidada existente
    Route::get('/empresas/{empresa}/subempresas', [EmpresaController::class, 'subempresas']);
    Route::get('/empresas/{empresa}/usuarios',    [EmpresaController::class, 'listarUsuarios']);

    // --- SUBEMPRESAS CRUD ---
    Route::get   ('/subempresas',                 [SubempresaController::class, 'index']);
    Route::post  ('/empresas/{empresa}/subempresas',[SubempresaController::class, 'store'])->middleware('permiso:subempresa:create');
    Route::get   ('/subempresas/{subempresa}',    [SubempresaController::class, 'show']);
    Route::put   ('/subempresas/{subempresa}',    [SubempresaController::class, 'update'])->middleware('permiso:subempresa:update');
    Route::delete('/subempresas/{subempresa}',    [SubempresaController::class, 'destroy'])->middleware('permiso:subempresa:delete');
    Route::get   ('/subempresas/{subempresa}/sucursales',[SubempresaController::class, 'sucursales']);

    // --- SUCURSALES CRUD ---
    Route::get   ('/sucursales',                  [SucursalController::class, 'index']);
    Route::post  ('/subempresas/{subempresa}/sucursales',[SucursalController::class, 'store'])->middleware('permiso:sucursal:create');
    Route::get   ('/sucursales/{sucursal}',       [SucursalController::class, 'show']);
    Route::put   ('/sucursales/{sucursal}',       [SucursalController::class, 'update'])->middleware('permiso:sucursal:update');
    Route::delete('/sucursales/{sucursal}',       [SucursalController::class, 'destroy'])->middleware('permiso:sucursal:delete');
    Route::get   ('/sucursales/{sucursal}/usuarios',[SucursalController::class, 'usuarios']);

    // Personalizacion del usuario

    // Usuario autenticado
    Route::get('/perfil', [AuthController::class, 'perfil'])
            ->middleware('verificar.activacion');
    
    Route::get('/usuario/personalizacion',[AuthController::class, 'obtenerPersonalizacion']);
    Route::put('/usuario/personalizacion', [AuthController::class, 'actualizarPersonalizacion']);


    Route::post('/activar-cuenta', [AuthController::class, 'activarCuenta']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/activar', [AuthController::class, 'verificarTokenActivacion']);
    
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
        Route::post('/sucursales', [SucursalController::class, 'store']);
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

    // Rutas de permisos
    Route::prefix('usuarios/{usuario}/permisos')
        ->middleware(['auth:api','checkPermiso:usuario:update'])
        ->group(function(){
            Route::get('/',    [UserPermissionController::class,'index']);
            Route::post('/',   [UserPermissionController::class,'store']);
            Route::delete('/{permiso}', [UserPermissionController::class,'destroy']);
    });


});
