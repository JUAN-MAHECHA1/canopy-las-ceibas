<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Un "work_day" representa el turno operativo de un día concreto.
        // Se abre una sola vez por fecha (unique) y define qué guías
        // recibirán pago ese día.
        Schema::create('work_days', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique();
            $table->boolean('activo')->default(true);
            $table->foreignId('abierto_por')->constrained('users')->restrictOnDelete();
            $table->timestamp('cerrado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_days');
    }
};
