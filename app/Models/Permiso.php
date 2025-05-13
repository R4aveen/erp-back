<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $fillable = ['clave', 'descripcion'];
    
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'permiso_rol');
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'permiso_usuario');
    }
}
