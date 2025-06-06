<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nombre','rut','descripcion'];
    
    public function subempresas() {
        return $this->hasMany(Subempresa::class);
    }
    
}
