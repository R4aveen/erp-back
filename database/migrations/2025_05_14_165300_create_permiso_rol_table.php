<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisoRolTable extends Migration
{
    public function up()
    {
        Schema::create('permiso_rol', function (Blueprint $table) {
            $table->foreignId('rol_id')
                  ->constrained('roles')
                  ->cascadeOnDelete();

            $table->foreignId('permiso_id')
                  ->constrained('permisos')
                  ->cascadeOnDelete();

            $table->primary(['rol_id', 'permiso_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permiso_rol');
    }
}
