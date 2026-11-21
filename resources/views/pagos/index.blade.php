@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Pagos</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <h5 class="mb-3">Citas atendidas pendientes de cobro</h5>

    @if ($citas->isEmpty())
        <div class="alert alert-info">No hay citas atendidas pendientes de pago.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white shadow-sm">
                <thead>
                    <tr>
                        <th>Fecha y hora</th>
                        <th>Paciente</th>
                        <th>Servicio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($citas as $cita)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($cita->fecha)) }} · {{ substr($cita->hora, 0, 5) }}</td>
                            <td>{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</td>
                            <td>{{ $cita->servicio->nombre }}</td>
                            <td><a href="{{ route('pagos.crear', $cita->id_cita) }}" class="btn btn-sm btn-primary">Cobrar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection