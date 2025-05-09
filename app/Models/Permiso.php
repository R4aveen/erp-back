<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    public function roles() {
        return $this->belongsToMany(Rol::class, 'permiso_rol');
    }
}
