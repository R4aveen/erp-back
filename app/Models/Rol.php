<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $fillable = ['nombre'];

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
}
