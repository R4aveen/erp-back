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
        Schema::table('personalizacion_usuarios', function (Blueprint $table) {
            $table->renameColumn('usuario', 'usuario_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personalizacion_usuarios', function (Blueprint $table) {
            $table->renameColumn('usuario_id', 'usuario');
        });

    }
};
