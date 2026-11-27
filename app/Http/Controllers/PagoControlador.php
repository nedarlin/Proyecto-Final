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

        return view('pagos.crear', ['cita' => $cita]);
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

        $request->validate([
            'metodo_pago'    => ['required', Rule::in(['Efectivo', 'Tarjeta', 'Transferencia'])],
            'precio'         => 'required|numeric|min:1|max:10000|decimal:0,2',
        ], [
            'metodo_pago.required'    => 'Selecciona un método de pago.',
            'precio.required'         => 'Escribe el precio de la consulta.',
            'precio.numeric'          => 'Ingresa un monto válido.',
            'precio.min'              => 'El precio mínimo es S/ 1.00.',
            'precio.max'              => 'El precio máximo es S/ 10,000.00.',
            'precio.decimal'          => 'Usa como máximo 2 decimales.',
        ]);

        $precio = round((float) $request->precio, 2);

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
            'monto'           => $precio,
            'metodo_pago'     => $request->metodo_pago,
            'estado_pago'     => 'Pagado',
            'nro_comprobante' => 'B001-' . str_pad($cita->id_cita, 6, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('pagos.comprobante', $cita->id_cita)
            ->with('exito', 'Pago registrado correctamente.');
    }

    public function historial(Request $request)
    {
        if (!session()->has('odontologo_id')) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Inicia sesión como odontólogo.']);
        }

        $q     = trim($request->query('q', ''));
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $desde = ($desde && date('Y-m-d', strtotime($desde)) === $desde) ? $desde : null;
        $hasta = ($hasta && date('Y-m-d', strtotime($hasta)) === $hasta) ? $hasta : null;

        if ($desde && $hasta && $desde > $hasta) {
            [$desde, $hasta] = [$hasta, $desde];
        }

        $consulta = Pago::join('cita', 'cita.id_cita', '=', 'pago.id_cita')
            ->join('paciente', 'paciente.id_paciente', '=', 'cita.id_paciente')
            ->where('cita.id_odontologo', session('odontologo_id'))
            ->select('pago.*');

        if ($desde) {
            $consulta->where('cita.fecha', '>=', $desde);
        }
        if ($hasta) {
            $consulta->where('cita.fecha', '<=', $hasta);
        }
        if ($q !== '') {
            $consulta->where(function ($w) use ($q) {
                $w->where('paciente.nombres', 'like', '%' . $q . '%')
                  ->orWhere('paciente.apellidos', 'like', '%' . $q . '%')
                  ->orWhereRaw("CONCAT(paciente.nombres, ' ', paciente.apellidos) like ?", ['%' . $q . '%']);
            });
        }

        $totalCobrado = (clone $consulta)->sum('pago.monto');

        $pagos = $consulta
            ->with(['cita.paciente', 'cita.servicio'])
            ->orderBy('cita.fecha', 'desc')
            ->orderBy('cita.hora', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('pagos.historial', [
            'pagos'        => $pagos,
            'totalCobrado' => $totalCobrado,
            'q'            => $q,
            'desde'        => $desde,
            'hasta'        => $hasta,
            'hayFiltros'   => $q !== '' || $desde || $hasta,
        ]);
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