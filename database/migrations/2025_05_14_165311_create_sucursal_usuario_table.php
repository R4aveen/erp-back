<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalUsuarioTable extends Migration
{
    public function up()
    {
        Schema::create('sucursal_usuario', function (Blueprint $table) {
            // forzar "sucursales" en vez de "sucursals"
            $table->foreignId('sucursal_id')
                ->constrained('sucursales')
                ->cascadeOnDelete();

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->cascadeOnDelete();

            $table->primary(['sucursal_id','usuario_id']);
        });

    }

    public function down()
    {
        Schema::dropIfExists('sucursal_usuario');
    }
}
