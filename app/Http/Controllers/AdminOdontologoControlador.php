<?php

namespace App\Http\Controllers;

use App\Models\OdontologoEspecialista;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminOdontologoControlador extends Controller
{
    private function sinAcceso()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión como administrador.']);
        }
        return null;
    }

    private function especialidades()
    {
        return Servicio::orderBy('nombre')->pluck('especialidad')->unique()->values();
    }

    private function esUnicoDeSuEspecialidad(OdontologoEspecialista $odontologo): bool
    {
        return OdontologoEspecialista::where('especialidad', $odontologo->especialidad)->count() <= 1;
    }

    private function reglas($id = null): array
    {
        $colegiatura = Rule::unique('odontologo_especialista', 'colegiatura');
        $correo      = Rule::unique('odontologo_especialista', 'correo');

        if ($id) {
            $colegiatura->ignore($id, 'id_odontologo');
            $correo->ignore($id, 'id_odontologo');
        }

        return [
            'nombres'      => 'required|string|max:80',
            'colegiatura'  => ['required', 'max:10', $colegiatura],
            'especialidad' => ['required', Rule::in($this->especialidades()->all())],
            'correo'       => [
                'required', 'email', 'max:100', $correo,
                Rule::unique('paciente', 'correo'),
                Rule::unique('administrador', 'correo'),
            ],
            'clave'        => [$id ? 'nullable' : 'required', 'min:8', 'max:50'],
        ];
    }

    private function mensajes(): array
    {
        return [
            'required'           => 'Este campo es obligatorio.',
            'max'                => 'Es demasiado largo (máximo :max caracteres).',
            'min'                => 'Debe tener al menos :min caracteres.',
            'email'              => 'Escribe un correo válido.',
            'in'                 => 'Selecciona una opción de la lista.',
            'colegiatura.unique' => 'Esta colegiatura ya está registrada.',
            'correo.unique'      => 'Este correo ya está registrado en el sistema.',
        ];
    }

    public function index()
    {
        if ($r = $this->sinAcceso()) {
            return $r;
        }

        $odontologos = OdontologoEspecialista::withCount('citas')
            ->orderBy('especialidad')
            ->orderBy('nombres')
            ->get();

        return view('admin.odontologos.index', ['odontologos' => $odontologos]);
    }

    public function crear()
    {
        if ($r = $this->sinAcceso()) {
            return $r;
        }

        return view('admin.odontologos.form', [
            'odontologo'    => null,
            'especialidades' => $this->especialidades(),
        ]);
    }

    public function guardar(Request $request)
    {
        if ($r = $this->sinAcceso()) {
            return $r;
        }

        $datos = $request->validate($this->reglas(), $this->mensajes());

        OdontologoEspecialista::create([
            'nombres'         => $datos['nombres'],
            'colegiatura'     => $datos['colegiatura'],
            'especialidad'    => $datos['especialidad'],
            'correo'          => $datos['correo'],
            'contrasena_hash' => Hash::make($datos['clave']),
        ]);

        return redirect()->route('admin.odontologos.index')
            ->with('exito', 'Odontólogo registrado correctamente.');
    }

    public function editar($id)
    {
        if ($r = $this->sinAcceso()) {
            return $r;
        }

        return view('admin.odontologos.form', [
            'odontologo'    => OdontologoEspecialista::findOrFail($id),
            'especialidades' => $this->especialidades(),
        ]);
    }

    public function actualizar(Request $request, $id)
    {
        if ($r = $this->sinAcceso()) {
            return $r;
        }

        $odontologo = OdontologoEspecialista::findOrFail($id);
        $datos = $request->validate($this->reglas($id), $this->mensajes());

        if ($datos['especialidad'] !== $odontologo->especialidad && $this->esUnicoDeSuEspecialidad($odontologo)) {
            return back()->withInput()->withErrors([
                'especialidad' => 'Es el único odontólogo de ' . $odontologo->especialidad .
                    '; ese servicio quedaría sin atención.',
            ]);
        }

        $campos = [
            'nombres'      => $datos['nombres'],
            'colegiatura'  => $datos['colegiatura'],
            'especialidad' => $datos['especialidad'],
            'correo'       => $datos['correo'],
        ];

        if (!empty($datos['clave'])) {
            $campos['contrasena_hash'] = Hash::make($datos['clave']);
        }

        $odontologo->update($campos);

        return redirect()->route('admin.odontologos.index')
            ->with('exito', 'Odontólogo actualizado correctamente.');
    }

    public function eliminar($id)
    {
        if ($r = $this->sinAcceso()) {
            return $r;
        }

        $odontologo = OdontologoEspecialista::findOrFail($id);

        $totalCitas = $odontologo->citas()->count();
        if ($totalCitas > 0) {
            return back()->withErrors([
                'eliminar' => 'No se puede eliminar a ' . $odontologo->nombres . ': tiene ' .
                    $totalCitas . ' cita(s) registrada(s).',
            ]);
        }

        if ($this->esUnicoDeSuEspecialidad($odontologo)) {
            return back()->withErrors([
                'eliminar' => 'No se puede eliminar a ' . $odontologo->nombres .
                    ': es el único de ' . $odontologo->especialidad . '.',
            ]);
        }

        $odontologo->delete();

        return redirect()->route('admin.odontologos.index')
            ->with('exito', 'Odontólogo eliminado correctamente.');
    }
}
