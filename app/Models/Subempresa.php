<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subempresa extends Model
{
    
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function sucursales()
    {
        return $this->hasMany(Sucursal::class);
    }
}
