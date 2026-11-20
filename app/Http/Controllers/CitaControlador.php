<?php

namespace App\Http\Controllers;

use App\Repositories\JsonRepositorio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CitaControlador extends Controller
{
    private JsonRepositorio $citas;

    public function __construct()
    {
        $this->citas = new JsonRepositorio('citas.json');
    }

    public function agendar(Request $request)
    {
        if (!session()->has('paciente_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión para agendar una cita.']);
        }

        $especialidades = array_column(config('clinica.servicios'), 'nombre');
        $especialidad = $request->query('especialidad');
        $fecha = $request->query('fecha');

        $odontologo = in_array($especialidad, $especialidades, true)
            ? $this->odontologoDe($especialidad)
            : null;

        $fechaValida = $fecha
            && date('Y-m-d', strtotime($fecha)) === $fecha
            && $fecha > date('Y-m-d');

        $horarios = [];
        if ($odontologo && $fechaValida) {
            foreach (config('clinica.horarios') as $hora) {
                $horarios[] = [
                    'hora'     => $hora,
                    'ocupado'  => $this->horarioOcupado($odontologo['id'], $fecha, $hora),
                ];
            }
        }

        $hayDisponibles = in_array(false, array_column($horarios, 'ocupado'), true);

        return view('citas.agendar', [
            'especialidades'  => $especialidades,
            'especialidad'    => $especialidad,
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

        $especialidades = array_column(config('clinica.servicios'), 'nombre');

        $datos = $request->validate([
            'especialidad' => ['required', Rule::in($especialidades)],
            'fecha'        => 'required|date_format:Y-m-d|after:today',
            'hora'         => ['required', Rule::in(config('clinica.horarios'))],
        ], [
            'hora.required' => 'Selecciona un horario.',
            'fecha.after'   => 'Elige una fecha a partir de mañana.',
        ]);

        $odontologo = $this->odontologoDe($datos['especialidad']);

        if ($this->horarioOcupado($odontologo['id'], $datos['fecha'], $datos['hora'])) {
            return redirect()
                ->route('citas.agendar', [
                    'especialidad' => $datos['especialidad'],
                    'fecha'        => $datos['fecha'],
                ])
                ->withErrors([
                    'hora' => 'El horario de las ' . $datos['hora'] . ' ya no está disponible. Elige otro horario.',
                ]);
        }

        $this->citas->crear([
            'paciente_id'        => session('paciente_id'),
            'odontologo_id'      => $odontologo['id'],
            'especialidad'       => $datos['especialidad'],
            'fecha'              => $datos['fecha'],
            'hora'               => $datos['hora'],
            'estado'             => 'Programada',
            'motivo_cancelacion' => null,
        ]);

        return redirect()->route('citas.agendar')->with(
            'exito',
            'Cita confirmada: ' . $datos['especialidad'] . ' con ' . $odontologo['nombre'] .
            ', el ' . date('d/m/Y', strtotime($datos['fecha'])) . ' a las ' . $datos['hora'] . '.'
        );
    }

    private function odontologoDe(string $especialidad): ?array
    {
        foreach (config('clinica.odontologos') as $odontologo) {
            if ($odontologo['especialidad'] === $especialidad) {
                return $odontologo;
            }
        }
        return null;
    }

    private function horarioOcupado(int $odontologoId, string $fecha, string $hora): bool
    {
        foreach ($this->citas->todos() as $cita) {
            if ($cita['odontologo_id'] == $odontologoId
                && $cita['fecha'] === $fecha
                && $cita['hora'] === $hora
                && $cita['estado'] !== 'Cancelada') {
                return true;
            }
        }
        return false;
    }
}