<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisoUsuarioTable extends Migration
{
    public function up()
    {
        Schema::create('permiso_usuario', function (Blueprint $table) {
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            $table->foreignId('permiso_id')
                  ->constrained('permisos')
                  ->cascadeOnDelete();

            $table->primary(['usuario_id', 'permiso_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permiso_usuario');
    }
}
