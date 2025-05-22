<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subempresa_usuario', function (Blueprint $table) {
            $table->unsignedBigInteger('subempresa_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            // Indice primario compuesto
            $table->primary(['subempresa_id', 'usuario_id']);

            // Aqui las claavesd primarias nicoide

            $table->foreign('subempresa_id')
                ->references('id')->on('subempresas')
                ->onDelete('cascade');
            $table->foreign('usuario_id')
                ->references('id')->on('usuarios')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subempresa_usuario');
    }
};
