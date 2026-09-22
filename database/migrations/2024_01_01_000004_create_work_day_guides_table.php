<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla pivote: qué guías estuvieron activos en cada work_day.
        // El pago del día se reparte SOLO entre los guías que aparecen aquí.
        Schema::create('work_day_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_day_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['work_day_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_day_guides');
    }
};
