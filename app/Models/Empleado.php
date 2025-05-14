<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
      'usuario_id', 'empresa_id', 'sucursal_id',
      'cargo', 'fecha_ingreso', 'salario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
