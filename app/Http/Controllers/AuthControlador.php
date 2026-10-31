<?php

namespace App\Http\Controllers;

use App\Repositories\JsonRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthControlador extends Controller
{
    private JsonRepositorio $pacientes;

    public function __construct()
    {
        $this->pacientes = new JsonRepositorio('pacientes.json');
    }

    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'nombres'   => 'required|regex:/^[\pL\s]+$/u|max:50',
            'apellidos' => 'required|regex:/^[\pL\s]+$/u|max:50',
            'dni'       => 'required|digits:8',
            'celular'   => 'required|digits:9',
            'correo'    => 'required|email',
            'clave'     => 'required|min:8',
            'terminos'  => 'accepted',
        ], [
            'dni.digits'       => 'Ingresa un número de 8 dígitos.',
            'celular.digits'   => 'Ingresa un número de 9 dígitos.',
            'clave.min'        => 'La contraseña debe tener mínimo 8 caracteres.',
            'terminos.accepted' => 'Debes aceptar los términos y la política de privacidad.',
        ]);

        if ($this->pacientes->buscarPor('correo', $datos['correo'])) {
            return back()->withInput()->withErrors([
                'correo' => 'Este correo ya está registrado. ¿Quieres iniciar sesión?',
            ]);
        }
        if ($this->pacientes->buscarPor('dni', $datos['dni'])) {
            return back()->withInput()->withErrors(['dni' => 'Este DNI ya está registrado.']);
        }

        $paciente = $this->pacientes->crear([
            'nombres'    => $datos['nombres'],
            'apellidos'  => $datos['apellidos'],
            'dni'        => $datos['dni'],
            'celular'    => $datos['celular'],
            'correo'     => $datos['correo'],
            'clave_hash' => Hash::make($datos['clave']),
            'numero_hc'      => 'HC-' . str_pad(count($this->pacientes->todos()) + 1, 5, '0', STR_PAD_LEFT),
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

        $paciente = $this->pacientes->buscarPor('correo', $request->correo);

        if ($paciente && Hash::check($request->clave, $paciente['clave_hash'])) {
            $request->session()->regenerate();
            session([
                'paciente_id'     => $paciente['id'],
                'paciente_nombre' => $paciente['nombres'],
            ]);
            session()->forget(['intentos', 'bloqueado_hasta']);
            return redirect()->route('inicio');
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