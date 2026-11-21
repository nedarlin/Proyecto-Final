<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Pago;
use App\Models\TransaccionPago;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PagoControlador extends Controller
{
    private function buscarCita($id)
    {
        return Cita::with(['paciente', 'servicio', 'odontologo', 'pago.transaccion'])
            ->where('id_cita', $id)
            ->where('id_odontologo', session('odontologo_id'))
            ->firstOrFail();
    }

    public function index()
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión como odontólogo.']);
        }

        $citas = Cita::with(['paciente', 'servicio'])
            ->where('id_odontologo', session('odontologo_id'))
            ->where('estado', 'Atendida')
            ->whereDoesntHave('pago')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('pagos.index', ['citas' => $citas]);
    }

    public function crear($id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $cita = $this->buscarCita($id);

        if ($cita->estado !== 'Atendida') {
            return redirect()->route('pagos.index')
                ->withErrors(['pago' => 'Solo se puede cobrar una cita en estado Atendida.']);
        }

        if ($cita->pago) {
            return redirect()->route('pagos.comprobante', $cita->id_cita);
        }

        return view('pagos.crear', [
            'cita'  => $cita,
            'total' => config('clinica.precio_servicio'),
        ]);
    }

    public function guardar(Request $request, $id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $cita = $this->buscarCita($id);

        if ($cita->estado !== 'Atendida' || $cita->pago) {
            return redirect()->route('pagos.index')
                ->withErrors(['pago' => 'Esta cita no se puede cobrar.']);
        }

        $total = config('clinica.precio_servicio');

        $request->validate([
            'metodo_pago'    => ['required', Rule::in(['Efectivo', 'Tarjeta', 'Transferencia'])],
            'monto_recibido' => 'required|numeric|min:' . $total,
        ], [
            'metodo_pago.required'    => 'Selecciona un método de pago.',
            'monto_recibido.required' => 'Ingresa el monto recibido.',
            'monto_recibido.numeric'  => 'Ingresa un monto válido.',
            'monto_recibido.min'      => 'El monto es menor al total (S/ ' . number_format($total, 2) . ').',
        ]);

        $rechazada = $request->has('simular_rechazo');

        $transaccion = TransaccionPago::create([
            'estado' => $rechazada ? 'Rechazada' : 'Aprobada',
        ]);

        if ($rechazada) {
            return back()->withInput()->withErrors([
                'pago' => 'El Sistema de Pago rechazó la transacción. Elige otro método.',
            ]);
        }
        Pago::create([
            'id_cita'         => $cita->id_cita,
            'id_transaccion'  => $transaccion->id_transaccion,
            'monto'           => $total,
            'metodo_pago'     => $request->metodo_pago,
            'estado_pago'     => 'Pagado',
            'nro_comprobante' => 'B001-' . str_pad($cita->id_cita, 6, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('pagos.comprobante', $cita->id_cita)
            ->with('exito', 'Pago registrado correctamente.');
    }

    public function comprobante($id)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login');
        }

        $cita = $this->buscarCita($id);

        if (!$cita->pago) {
            return redirect()->route('pagos.crear', $cita->id_cita);
        }

        return view('pagos.comprobante', ['cita' => $cita]);
    }
}