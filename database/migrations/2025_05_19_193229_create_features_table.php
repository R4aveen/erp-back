<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();              // identificador interno, ej: 'gestion.empresa'
            $table->string('texto');                        // label de menú
            $table->string('ruta');                         // ruta React, ej: '/gestion/empresa'
            $table->string('componente');                   // path del componente, ej: 'gestion/EmpresaPage'
            $table->string('icono')->nullable();            // nombre de icono HeroIcon
            $table->integer('orden')->default(0);           // orden en menú
            $table->string('grupo')->nullable();            // agrupación principal, ej: 'Gestión Admin'
            $table->string('subgrupo')->nullable();         // subgrupo dentro de ese grupo
            $table->timestamps();                           // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
