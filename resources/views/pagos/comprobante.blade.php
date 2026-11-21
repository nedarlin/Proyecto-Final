@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 640px;">
    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    <div class="card card-body shadow-sm">
        <h4 class="text-center mb-1">Comprobante electrónico</h4>
        <p class="text-center text-muted mb-4">N.° {{ $cita->pago->nro_comprobante }}</p>

        <ul class="list-unstyled mb-3">
            <li><strong>Paciente:</strong> {{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</li>
            <li><strong>DNI:</strong> {{ $cita->paciente->dni }}</li>
            <li><strong>Servicio:</strong> {{ $cita->servicio->nombre }}</li>
            <li><strong>Fecha de la cita:</strong> {{ date('d/m/Y', strtotime($cita->fecha)) }}</li>
            <li><strong>Odontólogo:</strong> {{ $cita->odontologo->nombres }}</li>
            <li><strong>Método de pago:</strong> {{ $cita->pago->metodo_pago }}</li>
            <li><strong>Estado:</strong> {{ $cita->pago->estado_pago }}</li>
            <li><strong>N.° de transacción:</strong> {{ $cita->pago->transaccion->id_transaccion }}
                ({{ $cita->pago->transaccion->estado }})</li>
        </ul>

        <div class="d-flex justify-content-between border-top pt-2 mb-3">
            <strong>Total pagado</strong>
            <strong>S/ {{ number_format($cita->pago->monto, 2) }}</strong>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('pagos.index') }}" class="btn btn-primary">Volver a Pagos</a>
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">Imprimir</button>
        </div>
    </div>
</div>
@endsection