<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Evaluación general', 'descripcion' => 'Examen clínico y diagnóstico inicial, con apoyo de tomografía 3D cuando se necesita.'],
            ['nombre' => 'Ortodoncia', 'descripcion' => 'Corrección de la posición dental con brackets metálicos, estéticos o alineadores.'],
            ['nombre' => 'Implantología oral', 'descripcion' => 'Colocación de implantes dentales con indicaciones y cuidados postoperatorios.'],
            ['nombre' => 'Cirugía dental', 'descripcion' => 'Extracción de piezas retenidas y otros procedimientos quirúrgicos.'],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio + ['especialidad' => $servicio['nombre']]);
        }
    }
}