<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subempresa extends Model
{
    
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'empresa_id',
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
