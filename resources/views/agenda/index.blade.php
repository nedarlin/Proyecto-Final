@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-1">Agenda del día</h2>
    <p class="text-center text-muted mb-4">{{ session('odontologo_nombre') }}</p>

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('agenda') }}" class="card card-body shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" value="{{ $fecha }}" class="form-control" required>
            </div>
            <div class="col-6 col-md-3 d-grid">
                <button class="btn btn-outline-primary">Ver agenda</button>
            </div>
            <div class="col-6 col-md-3 d-grid">
                <a href="{{ route('agenda') }}" class="btn btn-outline-secondary">Hoy</a>
            </div>
        </div>
    </form>

    <h5 class="mb-3">Citas del {{ date('d/m/Y', strtotime($fecha)) }}</h5>

    @if ($citas->isEmpty())
        <div class="alert alert-info">No tienes citas para esta fecha.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white shadow-sm">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($citas as $cita)
                        <tr>
                            <td>{{ substr($cita->hora, 0, 5) }}</td>
                            <td>{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</td>
                            <td>{{ $cita->paciente->dni }}</td>
                            <td>{{ $cita->servicio->nombre }}</td>
                            <td>
                                @php
                                    $colores = [
                                        'Programada'  => 'primary',
                                        'En atención' => 'warning',
                                        'Atendida'    => 'success',
                                        'Cancelada'   => 'secondary',
                                    ];
                                @endphp
                                <span class="badge text-bg-{{ $colores[$cita->estado] }}">{{ $cita->estado }}</span>
                            </td>
                            <td>
                                @if ($cita->estado === 'Programada')
                                    <form method="POST" action="{{ route('agenda.avanzar', $cita->id_cita) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Iniciar atención</button>
                                    </form>
                                @elseif ($cita->estado === 'En atención')
                                    <a href="{{ route('evaluaciones.crear', $cita->id_cita) }}"
                                       class="btn btn-sm btn-success">Registrar evaluación</a>
                                @elseif ($cita->estado === 'Atendida')
                                    @if ($cita->pago)
                                        <a href="{{ route('pagos.comprobante', $cita->id_cita) }}"
                                           class="btn btn-sm btn-outline-success">Comprobante</a>
                                    @else
                                        <a href="{{ route('pagos.crear', $cita->id_cita) }}"
                                           class="btn btn-sm btn-primary">Cobrar</a>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection