@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Registrar pago del servicio</h2>

    @error('pago')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card card-body shadow-sm">
                <h5>Resumen del servicio</h5>
                <ul class="list-unstyled mb-3">
                    <li><strong>Paciente:</strong> {{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</li>
                    <li><strong>Servicio:</strong> {{ $cita->servicio->nombre }}</li>
                    <li><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($cita->fecha)) }}</li>
                    <li><strong>Odontólogo:</strong> {{ $cita->odontologo->nombres }}</li>
                    <li><strong>Estado de la cita:</strong> <span class="badge text-bg-success">{{ $cita->estado }}</span></li>
                </ul>
                <div class="d-flex justify-content-between border-top pt-2">
                    <strong>Total a pagar</strong>
                    <strong id="total-pagar">S/ {{ number_format((float) old('precio', 0), 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <form id="form-pago" method="POST" action="{{ route('pagos.guardar', $cita->id_cita) }}"
                  class="card card-body shadow-sm">
                @csrf

                <label class="form-label">Método de pago *</label>
                <div class="d-flex flex-wrap gap-2 mb-1">
                    @foreach (['Efectivo', 'Tarjeta', 'Transferencia'] as $metodo)
                        <input type="radio" class="btn-check" name="metodo_pago" id="metodo-{{ $metodo }}"
                               value="{{ $metodo }}" @checked(old('metodo_pago') === $metodo)>
                        <label class="btn btn-outline-primary" for="metodo-{{ $metodo }}">{{ $metodo }}</label>
                    @endforeach
                </div>
                @error('metodo_pago') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                <div class="mb-3 mt-3">
                    <label class="form-label">Precio de la consulta (S/) *</label>
                    <input type="number" step="0.01" min="1" name="precio" id="precio" value="{{ old('precio') }}"
                           class="form-control @error('precio') is-invalid @enderror">
                    @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="simular_rechazo" id="simular_rechazo">
                    <label class="form-check-label text-muted" for="simular_rechazo">
                        Simular rechazo del Sistema de Pago (demostración)
                    </label>
                </div>

                <p class="text-muted small">Al confirmar, se emitirá el comprobante electrónico del servicio.</p>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Registrar pago</button>
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Volver</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var precio = document.getElementById('precio');
    var total = document.getElementById('total-pagar');
    precio.addEventListener('input', function () {
        var valor = parseFloat(precio.value);
        total.textContent = 'S/ ' + (isNaN(valor) ? 0 : valor).toFixed(2);
    });

    document.getElementById('form-pago').addEventListener('submit', function () {
        var boton = this.querySelector('button[type=submit]');
        boton.disabled = true;
        boton.textContent = 'Sistema de Pago: validando transacción… Procesando';
    });
</script>
@endsection
