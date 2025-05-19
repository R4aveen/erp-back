<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'features';
    protected $fillable = [
        'clave','texto','ruta','componente',
        'icono','orden','grupo','subgrupo',
    ];
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 
            'feature_role', 
            'feature_id', 
            'rol_id');
    }
}

