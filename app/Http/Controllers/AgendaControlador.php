<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class AgendaControlador extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión como odontólogo para ver la agenda.']);
        }

        $fecha = $request->query('fecha');
        if (!$fecha || date('Y-m-d', strtotime($fecha)) !== $fecha) {
            $fecha = date('Y-m-d');
        }

        $citas = Cita::with(['paciente', 'servicio', 'pago'])
            ->where('id_odontologo', session('odontologo_id'))
            ->where('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        return view('agenda.index', [
            'citas' => $citas,
            'fecha' => $fecha,
        ]);
    }
    public function avanzar($id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $cita = Cita::where('id_cita', $id)
            ->where('id_odontologo', session('odontologo_id'))
            ->firstOrFail();

        $siguiente = [
            'Programada' => 'En atención',
        ];

        if (!isset($siguiente[$cita->estado])) {
            return back()->withErrors(['estado' => 'Esta cita no se puede iniciar desde aquí.']);
        }

        $cita->update(['estado' => $siguiente[$cita->estado]]);

        return back()->with('exito', 'La cita de las ' . substr($cita->hora, 0, 5) . ' ahora está: ' . $cita->estado . '.');
    }
}