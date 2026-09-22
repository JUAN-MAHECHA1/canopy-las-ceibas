<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            // 'nombre' -> el cliente se registra por Nombre y Apellido (Utica Xtrema, Abacoa, Balseros)
            // 'codigo' -> el cliente se registra por código de reserva, ej: R37-27 (Contacto Extremo)
            $table->enum('tipo_registro', ['nombre', 'codigo'])->default('nombre');
            $table->string('color', 20)->nullable(); // color hex para identificar la empresa en reportes/UI
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
