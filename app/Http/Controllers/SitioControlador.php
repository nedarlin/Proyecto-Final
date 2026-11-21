<?php

namespace App\Http\Controllers;

use App\Models\OdontologoEspecialista;
use App\Models\Servicio;

class SitioControlador extends Controller
{
    public function inicio()
    {
        return view('inicio', ['servicios' => Servicio::all()]);
    }

    public function catalogo()
    {
        return view('catalogo', ['servicios' => Servicio::all()]);
    }

    public function servicio($id)
    {
        $servicio = Servicio::findOrFail($id);

        $odontologos = OdontologoEspecialista::where('especialidad', $servicio->especialidad)->get();

        return view('servicio', [
            'servicio'    => $servicio,
            'odontologos' => $odontologos,
        ]);
    }
}