<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Empresas ----------
        $uticaXtrema = Empresa::create(['nombre' => 'Utica Xtrema', 'tipo_registro' => 'nombre', 'color' => '#22c55e']);
        $abacoa       = Empresa::create(['nombre' => 'Abacoa', 'tipo_registro' => 'nombre', 'color' => '#3b82f6']);
        $contacto     = Empresa::create(['nombre' => 'Contacto Extremo', 'tipo_registro' => 'codigo', 'color' => '#ec4899']);
        $balseros     = Empresa::create(['nombre' => 'Balseros', 'tipo_registro' => 'nombre', 'color' => '#f97316']);

        // ---------- Admin ----------
        $admin = User::create([
            'name' => 'Administrador Canopy',
            'email' => 'admin@canopylasceibas.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ---------- Jefes (uno por empresa) ----------
        foreach ([$uticaXtrema, $abacoa, $contacto, $balseros] as $empresa) {
            User::create([
                'name' => 'Jefe ' . $empresa->nombre,
                'email' => strtolower(str_replace(' ', '.', $empresa->nombre)) . '@canopylasceibas.com',
                'password' => Hash::make('password'),
                'role' => 'jefe',
                'empresa_id' => $empresa->id,
            ]);
        }

        // ---------- Guías ----------
        // El liderazgo ROTA entre los 4, así que nadie queda marcado como líder
        // por defecto. El admin debe entrar a /admin/guias y asignar quién lidera
        // hoy (o el líder saliente lo transfiere desde /lider/turno).
        $nombresGuias = [
            'Juan Pablo Beltrán',
            'Juan Nicolás Molina',
            'Jesús Antonio Muñoz',
            'Juan David Mahecha',
        ];

        foreach ($nombresGuias as $nombre) {
            User::create([
                'name' => $nombre,
                'email' => strtolower(str_replace(' ', '.', $nombre)) . '@canopylasceibas.com',
                'password' => Hash::make('password'),
                'role' => 'guia',
                'es_lider' => false,
            ]);
        }

        // Asigna un primer líder para que el sistema tenga a alguien operando desde el día 1.
        User::guias()->first()?->update(['es_lider' => true]);
    }
}
