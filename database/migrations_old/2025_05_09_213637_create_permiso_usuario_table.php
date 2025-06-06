<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permiso_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permiso_id')->constrained()->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permiso_usuario');
    }
};
