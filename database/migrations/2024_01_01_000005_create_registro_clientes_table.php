<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_day_id')->constrained()->restrictOnDelete();
            // guía que digitó el registro (trazabilidad), el pago se reparte
            // entre TODOS los guías activos del work_day, no solo este
            $table->foreignId('guia_registrador_id')->constrained('users')->restrictOnDelete();

            $table->string('nombre_cliente')->nullable();  // si empresa.tipo_registro = nombre
            $table->string('codigo_reserva')->nullable();  // si empresa.tipo_registro = codigo (ej R37-27)
            $table->string('cedula')->nullable();

            $table->unsignedTinyInteger('cantidad_personas')->default(1);
            $table->time('hora_registro');

            $table->timestamps();

            $table->index(['empresa_id', 'work_day_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_clientes');
    }
};
