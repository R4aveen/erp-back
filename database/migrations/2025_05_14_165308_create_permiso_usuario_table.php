<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisoUsuarioTable extends Migration
{
    public function up()
    {
        Schema::create('permiso_usuario', function (Blueprint $table) {
            // Foreign keys
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            $table->foreignId('permiso_id')
                  ->constrained('permisos')
                  ->cascadeOnDelete();

            // Primary composite key
            $table->primary(['usuario_id', 'permiso_id']);

            // Pivot timestamps
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permiso_usuario');
    }
}
