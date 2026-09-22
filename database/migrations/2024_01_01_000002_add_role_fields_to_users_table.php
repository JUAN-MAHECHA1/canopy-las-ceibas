<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Solo 3 valores de rol. El liderazgo de turno NO es un rol aparte,
            // es un atributo del guía (ver es_lider), porque el líder sigue
            // registrando clientes como cualquier guía.
            $table->enum('role', ['admin', 'jefe', 'guia'])->default('guia')->after('email');

            // Solo relevante cuando role = 'jefe' (a qué empresa pertenece ese jefe)
            $table->foreignId('empresa_id')->nullable()->after('role')
                ->constrained('empresas')->nullOnDelete();

            // Solo relevante cuando role = 'guia'. Indica si hoy/actualmente puede
            // administrar el turno (abrir el día y activar guías).
            $table->boolean('es_lider')->default(false)->after('empresa_id');

            $table->string('cedula', 20)->nullable()->unique()->after('es_lider');
            $table->string('telefono', 20)->nullable()->after('cedula');
            $table->boolean('activo')->default(true)->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('empresa_id');
            $table->dropColumn(['role', 'es_lider', 'cedula', 'telefono', 'activo']);
        });
    }
};
