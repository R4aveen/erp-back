<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'email', 'password', 'activado'];
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
        $this->loadMissing(['permisos', 'roles.permisos']);

        if ($this->permisos->pluck('clave')->contains($clave)) {
            return true;
        }

        return $this->roles->flatMap->permisos
                            ->pluck('clave')
                            ->contains($clave);
    }

}
