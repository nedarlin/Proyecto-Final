<?php

namespace Database\Seeders;

use App\Models\OdontologoEspecialista;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OdontologoEspecialistaSeeder extends Seeder
{
    public function run(): void
    {
        $odontologos = [
            ['nombres' => 'Dra. Torres', 'colegiatura' => 'COP-10001', 'especialidad' => 'Ortodoncia', 'correo' => 'torres@odontosonrisas.pe'],
            ['nombres' => 'Dr. Ramírez', 'colegiatura' => 'COP-10002', 'especialidad' => 'Evaluación general', 'correo' => 'ramirez@odontosonrisas.pe'],
            ['nombres' => 'Dra. Salas', 'colegiatura' => 'COP-10003', 'especialidad' => 'Implantología oral', 'correo' => 'salas@odontosonrisas.pe'],
            ['nombres' => 'Dr. Vargas', 'colegiatura' => 'COP-10004', 'especialidad' => 'Cirugía dental', 'correo' => 'vargas@odontosonrisas.pe'],
        ];

        foreach ($odontologos as $odontologo) {
            OdontologoEspecialista::create($odontologo + ['contrasena_hash' => Hash::make('odonto1234')]);
        }
    }
}