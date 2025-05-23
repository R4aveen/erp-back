<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $fillable = ['clave','descripcion'];
    protected $table = 'permisos';

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'permiso_rol', 'permiso_id', 'rol_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'permiso_usuario');
    }
}
