<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    public function subempresas() {
        return $this->hasMany(Subempresa::class);
    }
    
}
