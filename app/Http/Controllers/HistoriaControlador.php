<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinica;
use App\Models\Paciente;
use Illuminate\Http\Request;

class HistoriaControlador extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión como odontólogo.']);
        }

        $q = trim($request->query('q', ''));
        $pacientes = null;

        if ($q !== '') {
            $pacientes = Paciente::with('historiaClinica')
                ->where('dni', $q)
                ->orWhereRaw("CONCAT(nombres, ' ', apellidos) LIKE ?", ['%' . $q . '%'])
                ->orderBy('apellidos')
                ->get();
        }

        return view('historias.index', ['q' => $q, 'pacientes' => $pacientes]);
    }

    public function ver($id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $historia = HistoriaClinica::with(['paciente', 'evaluaciones.cita.servicio', 'evaluaciones.cita.odontologo'])
            ->findOrFail($id);

        $evaluaciones = $historia->evaluaciones->sortByDesc(fn ($e) => $e->cita->fecha);

        return view('historias.ver', [
            'historia'     => $historia,
            'evaluaciones' => $evaluaciones,
        ]);
    }

    public function editar($id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $historia = HistoriaClinica::with('paciente')->findOrFail($id);

        return view('historias.editar', ['historia' => $historia]);
    }

    public function guardar(Request $request, $id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $historia = HistoriaClinica::findOrFail($id);

        $datos = $request->validate([
            'observaciones' => 'nullable|max:3000',
        ], [
            'observaciones.max' => 'Las observaciones no pueden superar los 3000 caracteres.',
        ]);

        $historia->update(['observaciones' => $datos['observaciones']]);

        return redirect()->route('historias.ver', $historia->id_historia)
            ->with('exito', 'Historia clínica actualizada.');
    }
}