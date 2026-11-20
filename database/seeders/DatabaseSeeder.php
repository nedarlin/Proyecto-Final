<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // El orden importa: primero las tablas de las que otras dependen
        $this->call([
            ServicioSeeder::class,
            OdontologoEspecialistaSeeder::class,
            PacienteSeeder::class,
        ]);
    }
}