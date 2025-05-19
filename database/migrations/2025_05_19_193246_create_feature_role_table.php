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
        Schema::create('feature_role', function (Blueprint $table) {
            // Sin id auto: la PK es la combinación de feature_id + rol_id
            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('rol_id');
            $table->primary(['feature_id', 'rol_id']);

            // Claves foráneas
            $table->foreign('feature_id')
                  ->references('id')->on('features')
                  ->onDelete('cascade');

            $table->foreign('rol_id')
                  ->references('id')->on('roles')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_role');
    }
};
