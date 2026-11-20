<?php

namespace App\Http\Controllers;

class SitioControlador extends Controller
{
    public function inicio()
    {
        return view('inicio', ['servicios' => config('clinica.servicios')]);
    }
}