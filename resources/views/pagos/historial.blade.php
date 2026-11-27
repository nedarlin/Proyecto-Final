@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Historial de pagos</h2>

    <form method="GET" action="{{ route('pagos.historial') }}" class="card card-body shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label">Paciente (nombre o apellido)</label>
                <input type="text" name="q" value="{{ $q }}" class="form-control" maxlength="80">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" value="{{ $desde }}" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" value="{{ $hasta }}" class="form-control">
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('pagos.historial') }}" class="btn btn-outline-secondary flex-fill">Limpiar</a>
            </div>
        </div>
        <div class="form-text mt-2">La fecha es la de la cita. Para ver un solo día, pon la misma fecha en Desde y Hasta.</div>
    </form>

    @if ($pagos->total() === 0)
        <div class="alert alert-info">
            {{ $hayFiltros ? 'No se encontraron pagos con esos filtros.' : 'Todavía no hay pagos registrados.' }}
        </div>
    @else
        <p class="text-muted">
            {{ $pagos->total() }} pago(s) · Total cobrado:
            <strong>S/ {{ number_format($totalCobrado, 2) }}</strong>
        </p>

        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white shadow-sm">
                <thead>
                    <tr>
                        <th>Fecha de la cita</th>
                        <th>Paciente</th>
                        <th>Servicio</th>
                        <th>Método</th>
                        <th>N.° comprobante</th>
                        <th class="text-end">Monto</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pagos as $pago)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($pago->cita->fecha)) }} · {{ substr($pago->cita->hora, 0, 5) }}</td>
                            <td>{{ $pago->cita->paciente->nombres }} {{ $pago->cita->paciente->apellidos }}</td>
                            <td>{{ $pago->cita->servicio->nombre }}</td>
                            <td>{{ $pago->metodo_pago }}</td>
                            <td>{{ $pago->nro_comprobante }}</td>
                            <td class="text-end">S/ {{ number_format($pago->monto, 2) }}</td>
                            <td>
                                <a href="{{ route('pagos.comprobante', $pago->id_cita) }}"
                                   class="btn btn-sm btn-outline-primary">Ver comprobante</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $pagos->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection
