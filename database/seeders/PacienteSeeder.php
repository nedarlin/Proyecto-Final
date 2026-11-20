<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        $paciente = Paciente::create([
            'nombres'         => 'Juan Carlos',
            'apellidos'       => 'Pérez Gómez',
            'dni'             => '12345678',
            'correo'          => 'prueba@correo.com',
            'celular'         => '987654321',
            'contrasena_hash' => Hash::make('prueba1234'),
        ]);

        $paciente->historiaClinica()->create([
            'numero_hc'      => 'HC-00001',
            'fecha_apertura' => date('Y-m-d'),
        ]);
    }
}