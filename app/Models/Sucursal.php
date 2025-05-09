<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    public function subempresa() {
        return $this->belongsTo(Subempresa::class);
    }
    public function usuarios() {
        return $this->belongsToMany(Usuario::class, 'sucursal_usuario');
    }

}
