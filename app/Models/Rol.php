<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    public function permisos() {
        return $this->belongsToMany(Permiso::class, 'permiso_rol');
    }
}
