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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique(); // Ej: Artesco, Vinifan, Stanford
            $table->string('image')->nullable(); // Opcional: Logo de la marca
            $table->smallInteger('state')->default(1); // 1: Activo, 2: Inactivo
            $table->timestamps();
            $table->softDeletes(); // Recomendado para no borrar historial de ventas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
