<?php

namespace App\Http\Controllers;

use App\Models\Servicio;

class SitioControlador extends Controller
{
    public function inicio()
    {
        return view('inicio', ['servicios' => Servicio::all()]);
    }
}