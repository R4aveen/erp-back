<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmpresaUsuarioRol extends Pivot
{
    protected $table = 'empresa_usuario_rol';
    protected $guarded = [];
}
