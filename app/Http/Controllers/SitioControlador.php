<?php

namespace App\Http\Controllers;

class SitioControlador extends Controller
{
    public function inicio()
    {
        return view('inicio');
    }

    public function catalogo()
    {
        if (!session()->has('paciente_id')) {
            return redirect()->route('login');
        }
        $servicios = [
            ['nombre' => 'Evaluacion general', 'descripcion' => 'Examen clinico y diagnostico inicial del paciente.'],
            ['nombre' => 'Ortodoncia', 'descripcion' => 'Brackets metalicos, esteticos o alineadores.'],
            ['nombre' => 'Implantologia oral', 'descripcion' => 'Colocacion de implantes dentales.'],
            ['nombre' => 'Cirugía dental', 'descripcion' => 'Extraccion de piezas retenidas y otras cirugias.'],
        ];
        return view('catalogo', ['servicios' => $servicios]);
    }
}