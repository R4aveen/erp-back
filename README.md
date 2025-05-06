# ERP Backend - Laravel 12 + JWT

Proyecto base de un sistema ERP privado para la gestión de empresas, sucursales, usuarios, roles, clientes, proveedores y stock. Desarrollado en **Laravel 12**, con autenticación por **JWT**, orientado a una arquitectura RESTful y preparado para ser consumido por un frontend independiente en React + TypeScript.

---

##  Tecnologías y dependencias principales

* Laravel 12.12
* PHP 8.2+
* MySQL
* Laragon (entorno local)
* Tymon JWT Auth (`tymon/jwt-auth`)
* Composer
* Git

---

## 🔧 Instalación

```bash
composer create-project laravel/laravel erp-backend
cd erp-backend
cp .env.example .env
```

### Configura el .env

```dotenv
DB_DATABASE=erp_db
DB_USERNAME=root
DB_PASSWORD=
JWT_SECRET=... (se genera más abajo)
```

### Base de datos

```sql
CREATE DATABASE erp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Dependencias JWT

```bash
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

---

##  Comandos usados en el proyecto

```bash
# Inicializar proyecto
composer create-project laravel/laravel erp-backend

# Iniciar git
cd erp-backend
git init

# Crear JWT y config
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

# Modelos y migraciones
php artisan make:model Usuario -m
php artisan make:model Empresa -m
php artisan make:model Sucursal -m
php artisan make:model Rol -m
php artisan make:migration create_empresa_usuario_rol_table
php artisan make:migration create_sucursal_usuario_table

# Middleware personalizado
php artisan make:middleware VerificarAccesoEmpresa

# Controladores
php artisan make:controller AuthController
php artisan make:controller EmpresaController
php artisan make:controller ProductoController

# Migrar
php artisan migrate

# Servidor local
php artisan serve
```

---

##  Arquitectura y flujo

* Cada **usuario** está ligado a una o más empresas.
* Las empresas pueden tener **sucursales**.
* El **middleware** `VerificarAccesoEmpresa` asegura que solo accedan usuarios con permisos sobre esa empresa.
* JWT se usa para autenticar y validar peticiones seguras.

---

##  Rutas protegidas y Middleware

```php
Route::middleware([VerificarAccesoEmpresa::class])->group(function () {
    Route::get('/empresa/{empresa}/usuarios', [EmpresaController::class, 'listarUsuarios']);
    Route::post('/empresa/{empresa}/producto', [ProductoController::class, 'crear']);
});
```

```php
// Middleware VerificarAccesoEmpresa.php
$usuario = JWTAuth::parseToken()->authenticate();
$empresaId = $request->route('empresa');
if (!$usuario->empresas()->where('empresas.id', $empresaId)->exists()) {
    return response()->json(['error' => 'Acceso denegado a esta empresa'], 403);
}
```

---

##  Modelo Usuario y Auth

```php
class Usuario extends Authenticatable implements JWTSubject {
    protected $fillable = ['nombre', 'email', 'password'];
    protected $hidden = ['password'];

    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }
}
```

```php
// AuthController
public function registrar(Request $request) {...}
public function login(Request $request) {...}
public function perfil() {...}
public function logout() {...}
```

---

##  Endpoints actuales

| Método | Ruta                       | Controlador                       | Protegido        |
| ------ | -------------------------- | --------------------------------- | ---------------- |
| POST   | /api/registro              | AuthController\@registrar         | No               |
| POST   | /api/login                 | AuthController\@login             | No               |
| GET    | /api/perfil                | AuthController\@perfil            | JWT              |
| POST   | /api/logout                | AuthController\@logout            | JWT              |
| POST   | /api/refresh               | JWT                               | JWT              |
| GET    | /api/empresa/{id}/usuarios | EmpresaController\@listarUsuarios | JWT + Middleware |
| POST   | /api/empresa/{id}/producto | ProductoController\@crear         | JWT + Middleware |

---

##  Siguientes pasos recomendados

* Completar migraciones para empresas, roles, relaciones.
* Crear seeders para roles y un usuario admin.
* Implementar relaciones en modelos (`empresas()`, `roles()`...)
* Agregar tests con Postman para login, acción con token.
* Desarrollar CRUD de productos, stock y sincronización web-bodega.

---

##  Estado del proyecto

Backend en desarrollo
Aún no se conecta al frontend (React TS)
Estructura multiempresa funcional
JWT + protección por empresa funcional

---

## 🔧 Requiere PHP 8.2+ y MySQL. Recomendado usar Laragon para entorno local.
