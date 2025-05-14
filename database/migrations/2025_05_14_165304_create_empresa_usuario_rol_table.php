<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaUsuarioRolTable extends Migration
{
    public function up()
    {
        Schema::create('empresa_usuario_rol', function (Blueprint $table) {
            $table->foreignId('empresa_id')
                ->constrained('empresas')
                ->cascadeOnDelete();

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->cascadeOnDelete();

            // aquí también forzamos "roles"
            $table->foreignId('rol_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('empresa_usuario_rol');
    }
}
