<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $fillable = ['slug','nombre','descripcion'];
    protected $table = 'roles';
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol', 'rol_id', 'permiso_id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_role', 'rol_id', 'feature_id');
    }

    public function usuarios()
    {
        return $this->hasManyThrough(
            Usuario::class,
            EmpresaUsuarioRol::class,
            'rol_id',
            'id',
            'id',
            'usuario_id'
        );
    }
}
