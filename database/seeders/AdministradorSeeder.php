<?php

namespace Database\Seeders;

use App\Models\Administrador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    public function run(): void
    {
        $correo = config('clinica.admin_correo');
        $clave  = config('clinica.admin_clave');

        if (!$correo || !$clave) {
            $this->command->error('Define ADMIN_CORREO y ADMIN_CLAVE en el archivo .env');
            return;
        }

        Administrador::updateOrCreate(
            ['correo' => $correo],
            ['nombres' => 'Superadministrador', 'contrasena_hash' => Hash::make($clave)]
        );
    }
}
