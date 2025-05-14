<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalizacionUsuario extends Model
{
    protected $table = 'personalizacion_usuarios';

    protected $fillable = [
        'usuario',
        'tema',
        'font_size',
    ];
}
