<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('personalizacion_usuarios', 'usuario')) {
            Schema::table('personalizacion_usuarios', function (Blueprint $table) {
                $table->renameColumn('usuario', 'usuario_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('personalizacion_usuarios', 'usuario_id')) {
            Schema::table('personalizacion_usuarios', function (Blueprint $table) {
                $table->renameColumn('usuario_id', 'usuario');
            });
        }
    }
};
