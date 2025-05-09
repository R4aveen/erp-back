<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
## use Illuminate\Database\Eloquent\Model;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $table = 'usuarios';

    protected $fillable = ['nombre', 'email', 'password'];

    protected $hidden = ['password'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function empresa() {
        return $this->belongsToMany(Empresa::class, 'empresa_usuario_rol');
    }
    public function subempresa() {
        return $this->belongsTo(Subempresa::class);
    }
    public function roles() {
        return $this->belongsToMany(Rol::class, 'empresa_usuario_rol');
    }
    public function sucursales() {
        return $this->belongsToMany(Sucursal::class, 'sucursal_usuario');
    }
    public function tienePermiso($clave)
    {
        foreach ($this->roles as $rol) {
            if ($rol->permisos->contains('clave', $clave)) {
                return true;
            }
        }
        return false;
    }
}
