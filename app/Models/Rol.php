<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $fillable = ['slug','nombre','descripcion'];
    protected $table = 'roles';
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol');
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
