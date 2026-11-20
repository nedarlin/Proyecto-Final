<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\OdontologoEspecialista;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CitaControlador extends Controller
{
    public function agendar(Request $request)
    {
        if (!session()->has('paciente_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión para agendar una cita.']);
        }

        $fecha = $request->query('fecha');

        $servicio = Servicio::find($request->query('servicio'));
        $odontologo = $servicio
            ? OdontologoEspecialista::where('especialidad', $servicio->especialidad)->first()
            : null;

        $fechaValida = $fecha
            && date('Y-m-d', strtotime($fecha)) === $fecha
            && $fecha > date('Y-m-d');

        $horarios = [];
        if ($odontologo && $fechaValida) {
            $ocupadas = Cita::where('id_odontologo', $odontologo->id_odontologo)
                ->where('fecha', $fecha)
                ->where('estado', '!=', 'Cancelada')
                ->pluck('hora')
                ->map(fn ($h) => substr($h, 0, 5))
                ->all();

            foreach (config('clinica.horarios') as $hora) {
                $horarios[] = [
                    'hora'    => $hora,
                    'ocupado' => in_array($hora, $ocupadas, true),
                ];
            }
        }

        $hayDisponibles = in_array(false, array_column($horarios, 'ocupado'), true);

        return view('citas.agendar', [
            'servicios'       => Servicio::all(),
            'servicio'        => $servicio,
            'fecha'           => $fecha,
            'odontologo'      => $odontologo,
            'horarios'        => $horarios,
            'hayDisponibles'  => $hayDisponibles,
            'mostrarHorarios' => $odontologo && $fechaValida,
        ]);
    }

    public function confirmar(Request $request)
    {
        if (!session()->has('paciente_id')) {
            return redirect()->route('login');
        }

        $datos = $request->validate([
            'servicio' => 'required|exists:servicio,id_servicio',
            'fecha'    => 'required|date_format:Y-m-d|after:today',
            'hora'     => ['required', Rule::in(config('clinica.horarios'))],
        ], [
            'servicio.required' => 'Selecciona un servicio.',
            'hora.required'     => 'Selecciona un horario.',
            'fecha.after'       => 'Elige una fecha a partir de mañana.',
        ]);

        $servicio = Servicio::findOrFail($datos['servicio']);
        $odontologo = OdontologoEspecialista::where('especialidad', $servicio->especialidad)->firstOrFail();
        $hora = $datos['hora'] . ':00';
        $cita = Cita::where('id_odontologo', $odontologo->id_odontologo)
            ->where('fecha', $datos['fecha'])
            ->where('hora', $hora)
            ->first();

        if ($cita && $cita->estado !== 'Cancelada') {
            return redirect()
                ->route('citas.agendar', ['servicio' => $servicio->id_servicio, 'fecha' => $datos['fecha']])
                ->withErrors([
                    'hora' => 'El horario de las ' . $datos['hora'] . ' ya no está disponible. Elige otro horario.',
                ]);
        }

        $campos = [
            'id_paciente'        => session('paciente_id'),
            'id_odontologo'      => $odontologo->id_odontologo,
            'id_servicio'        => $servicio->id_servicio,
            'fecha'              => $datos['fecha'],
            'hora'               => $hora,
            'estado'             => 'Programada',
            'motivo_cancelacion' => null,
        ];

        if ($cita) {
            $cita->update($campos);
        } else {
            Cita::create($campos);
        }

        return redirect()->route('citas.agendar')->with(
            'exito',
            'Cita confirmada: ' . $servicio->nombre . ' con ' . $odontologo->nombres .
            ', el ' . date('d/m/Y', strtotime($datos['fecha'])) . ' a las ' . $datos['hora'] . '.'
        );
    }
}