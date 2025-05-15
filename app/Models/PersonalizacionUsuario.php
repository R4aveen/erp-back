<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalizacionUsuario extends Model
{
    protected $table = 'personalizacion_usuarios';
    protected $fillable = [
        'usuario_id',
        'tema',
        'font_size',
        // etc.
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}


