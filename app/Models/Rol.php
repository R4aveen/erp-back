<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['slug', 'nombre', 'descripcion'];

    public function permisos()
    {
        return $this->belongsToMany(
            Permiso::class,
            'permiso_rol',
            'rol_id',
            'permiso_id'
        )
        ->withTimestamps();
    }

    public function features()
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_role',
            'rol_id',
            'feature_id'
        )
        ->withTimestamps();
    }

    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'empresa_usuario_rol',
            'rol_id',
            'usuario_id'
        )
        ->withPivot('empresa_id')
        ->withTimestamps();
    }
}
