<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\EvaluacionDiagnostico;
use Illuminate\Http\Request;

class EvaluacionControlador extends Controller
{
    private function buscarCita($id)
    {
        return Cita::with(['paciente.historiaClinica', 'servicio'])
            ->where('id_cita', $id)
            ->where('id_odontologo', session('odontologo_id'))
            ->firstOrFail();
    }

    private function problema($cita)
    {
        if ($cita->estado !== 'En atención') {
            return 'La cita debe estar En atención para registrar la evaluación.';
        }
        if ($cita->evaluacion()->exists()) {
            return 'Esta cita ya tiene una evaluación registrada.';
        }
        return null;
    }

    public function crear($id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión como odontólogo.']);
        }

        $cita = $this->buscarCita($id);

        if ($error = $this->problema($cita)) {
            return redirect()->route('agenda', ['fecha' => $cita->fecha])->withErrors(['estado' => $error]);
        }

        $ultimaAtencion = Cita::where('id_paciente', $cita->id_paciente)
            ->where('estado', 'Atendida')
            ->max('fecha');

        return view('evaluaciones.crear', [
            'cita'           => $cita,
            'ultimaAtencion' => $ultimaAtencion,
        ]);
    }

    public function guardar(Request $request, $id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $cita = $this->buscarCita($id);

        if ($error = $this->problema($cita)) {
            return redirect()->route('agenda', ['fecha' => $cita->fecha])->withErrors(['estado' => $error]);
        }

        $datos = $request->validate([
            'motivo_consulta'      => 'required|max:200',
            'hallazgos_clinicos'   => 'nullable',
            'diagnostico'          => 'required',
            'tratamiento_sugerido' => 'nullable|max:200',
        ], [
            'motivo_consulta.required' => 'Campo obligatorio.',
            'diagnostico.required'     => 'Campo obligatorio.',
        ]);

        EvaluacionDiagnostico::create([
            'id_historia'          => $cita->paciente->historiaClinica->id_historia,
            'id_cita'              => $cita->id_cita,
            'motivo_consulta'      => $datos['motivo_consulta'],
            'hallazgos_clinicos'   => $datos['hallazgos_clinicos'] ?? null,
            'diagnostico'          => $datos['diagnostico'],
            'tratamiento_sugerido' => $datos['tratamiento_sugerido'] ?? null,
        ]);

        $cita->update(['estado' => 'Atendida']);

        return redirect()->route('agenda', ['fecha' => $cita->fecha])
            ->with('exito', 'Evaluación guardada. La cita quedó Atendida.');
    }
}