<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'email', 'password'];
    protected $hidden   = ['password'];

    /* === JWT === */
    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims(): array { return []; }

    /* === Relaciones jerárquicas === */
    public function empresas()  { return $this->belongsToMany(Empresa::class,   'empresa_usuario_rol'); }
    public function roles()     { return $this->belongsToMany(Rol::class,      'empresa_usuario_rol'); }
    public function sucursales(){ return $this->belongsToMany(Sucursal::class, 'sucursal_usuario');    }
    public function subempresa(){ return $this->belongsTo(Subempresa::class); }   // si existe clave foránea directa

    /* === Permisos === */
    public function permisos()  { return $this->belongsToMany(Permiso::class, 'permiso_usuario'); }

    /** ¿El usuario posee X permiso (directo o por algún rol)? */
    public function tienePermiso(string $clave): bool
    {
        if ($this->permisos->pluck('clave')->contains($clave)) { return true; }

        return $this->roles->flatMap->permisos      // une permisos de cada rol
                         ->pluck('clave')
                         ->contains($clave);
    }
}
