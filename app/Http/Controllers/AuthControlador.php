<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\OdontologoEspecialista;

class AuthControlador extends Controller
{
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'nombres'   => 'required|regex:/^[\pL\s]+$/u|max:80',
            'apellidos' => 'required|regex:/^[\pL\s]+$/u|max:80',
            'dni'       => 'required|digits:8|unique:paciente,dni',
            'celular'   => 'required|digits:9',
            'correo'    => 'required|email|max:100|unique:paciente,correo',
            'clave'     => 'required|min:8',
            'terminos'  => 'accepted',
        ], [
            'dni.digits'        => 'Ingresa un número de 8 dígitos.',
            'dni.unique'        => 'Este DNI ya está registrado.',
            'celular.digits'    => 'Ingresa un número de 9 dígitos.',
            'correo.unique'     => 'Este correo ya está registrado. ¿Quieres iniciar sesión?',
            'clave.min'         => 'La contraseña debe tener mínimo 8 caracteres.',
            'terminos.accepted' => 'Debes aceptar los términos y la política de privacidad.',
        ]);

        $paciente = Paciente::create([
            'nombres'         => $datos['nombres'],
            'apellidos'       => $datos['apellidos'],
            'dni'             => $datos['dni'],
            'correo'          => $datos['correo'],
            'celular'         => $datos['celular'],
            'contrasena_hash' => Hash::make($datos['clave']),
        ]);

        $paciente->historiaClinica()->create([
            'numero_hc'      => 'HC-' . str_pad($paciente->id_paciente, 5, '0', STR_PAD_LEFT),
            'fecha_apertura' => date('Y-m-d'),
        ]);

        return redirect()->route('login')->with('exito', 'Cuenta creada. Ya puedes iniciar sesión.');
    }

    public function mostrarLogin()
    {
        return view('auth.login');
    }

        public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'clave'  => 'required',
        ]);

        if (session('bloqueado_hasta') && time() < session('bloqueado_hasta')) {
            return back()->withErrors(['correo' => 'Acceso bloqueado temporalmente. Intenta en 5 minutos.']);
        }

        $paciente = Paciente::where('correo', $request->correo)->first();

        $odontologo = $paciente ? null : OdontologoEspecialista::where('correo', $request->correo)->first();

        if ($paciente && Hash::check($request->clave, $paciente->contrasena_hash)) {
            $request->session()->regenerate();
            session([
                'paciente_id'     => $paciente->id_paciente,
                'paciente_nombre' => $paciente->nombres,
            ]);
            session()->forget(['intentos', 'bloqueado_hasta']);
            return redirect()->route('inicio');
        }

        if ($odontologo && Hash::check($request->clave, $odontologo->contrasena_hash)) {
            $request->session()->regenerate();
            session([
                'odontologo_id'     => $odontologo->id_odontologo,
                'odontologo_nombre' => $odontologo->nombres,
            ]);
            session()->forget(['intentos', 'bloqueado_hasta']);
            return redirect()->route('agenda');
        }

        $intentos = session('intentos', 0) + 1;
        if ($intentos >= 3) {
            session(['bloqueado_hasta' => time() + 300, 'intentos' => 0]);
            return back()->withErrors(['correo' => 'Superaste los intentos. Acceso bloqueado por 5 minutos.']);
        }
        session(['intentos' => $intentos]);
        return back()->withInput()->withErrors([
            'correo' => 'Correo o contraseña incorrectos. Te quedan ' . (3 - $intentos) . ' intentos.',
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}