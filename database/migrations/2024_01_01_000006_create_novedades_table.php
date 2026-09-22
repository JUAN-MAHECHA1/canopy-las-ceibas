<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guia_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('work_day_id')->nullable()->constrained('work_days')->nullOnDelete();
            $table->enum('tipo', ['cliente', 'equipo', 'incidente']);
            $table->string('titulo')->nullable();
            $table->text('descripcion');
            $table->timestamps();

            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novedades');
    }
};
