<?php

namespace App\Http\Controllers;

class SitioControlador extends Controller
{
    public function inicio()
    {
        $servicios = [
            [
                'nombre' => 'Evaluacion general',
                'descripcion' => 'Examen clinico y diagnóstico inicial, con apoyo de tomografia 3D cuando se necesita.',
            ],
            [
                'nombre' => 'Ortodoncia',
                'descripcion' => 'Correccion de la posicion dental con brackets metalicos, esteticos o alineadores.',
            ],
            [
                'nombre' => 'Implantologia oral',
                'descripcion' => 'Colocacion de implantes dentales con indicaciones y cuidados postoperatorios.',
            ],
            [
                'nombre' => 'Cirugía dental',
                'descripcion' => 'Extraccion de piezas retenidas y otros procedimientos quirurgicos.',
            ],
        ];

        return view('inicio', ['servicios' => $servicios]);
    }
}