@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Mis citas</h2>

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

    @if (!$tieneCitas)
        <div class="card card-body text-center shadow-sm">
            <p class="mb-3">Aún no tienes citas registradas.</p>
            <div><a href="{{ route('citas.agendar') }}" class="btn btn-primary">Agendar cita</a></div>
        </div>
    @else
        <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach (['Todas' => 'Todas', 'Programada' => 'Programadas', 'Atendida' => 'Atendidas', 'Cancelada' => 'Canceladas'] as $valor => $texto)
                <a href="{{ route('citas.mis', ['estado' => $valor]) }}"
                   class="btn btn-sm {{ $filtro === $valor ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $texto }}
                </a>
            @endforeach
        </div>

        <div class="alert alert-warning py-2">
            Solo puedes cancelar citas programadas con al menos 24 horas de anticipación.
        </div>

        @if ($citas->isEmpty())
            <div class="alert alert-info">No hay citas en esta categoría.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm">
                    <thead>
                        <tr>
                            <th>Fecha y hora</th>
                            <th>Especialidad</th>
                            <th>Odontólogo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citas as $cita)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($cita->fecha)) }} · {{ substr($cita->hora, 0, 5) }}</td>
                                <td>{{ $cita->servicio->nombre }}</td>
                                <td>{{ $cita->odontologo->nombres }}</td>
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
                                <td class="text-nowrap">
                                    <a href="{{ route('citas.mis', ['estado' => $filtro, 'ver' => $cita->id_cita]) }}"
                                       class="btn btn-sm btn-outline-secondary">Ver detalle</a>

                                    @if ($cita->estado === 'Programada')
                                        @if ($cita->puedeCancelarse())
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#cancelar-{{ $cita->id_cita }}">
                                                Cancelar cita
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-danger" disabled
                                                    title="Faltan menos de 24 horas">Cancelar cita</button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if ($detalle)
            <div class="card card-body shadow-sm mt-3">
                <h5>Detalle de la cita</h5>
                <ul class="list-unstyled mb-0">
                    <li><strong>Especialidad:</strong> {{ $detalle->servicio->nombre }}</li>
                    <li><strong>Odontólogo:</strong> {{ $detalle->odontologo->nombres }}</li>
                    <li><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($detalle->fecha)) }}</li>
                    <li><strong>Hora:</strong> {{ substr($detalle->hora, 0, 5) }}</li>
                    <li><strong>Estado:</strong> {{ $detalle->estado }}</li>
                    @if ($detalle->estado === 'Cancelada')
                        <li><strong>Motivo de cancelación:</strong> {{ $detalle->motivo_cancelacion }}</li>
                    @endif
                </ul>
            </div>
        @endif

        @foreach ($citas as $cita)
            @if ($cita->puedeCancelarse())
                <div class="modal fade" id="cancelar-{{ $cita->id_cita }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('citas.cancelar', $cita->id_cita) }}" class="modal-content">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Cancelar cita</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    {{ $cita->servicio->nombre }} con {{ $cita->odontologo->nombres }},
                                    el {{ date('d/m/Y', strtotime($cita->fecha)) }} a las {{ substr($cita->hora, 0, 5) }}.
                                </p>
                                <label class="form-label">Motivo de la cancelación</label>
                                <input type="text" name="motivo" class="form-control" maxlength="150" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Volver</button>
                                <button type="submit" class="btn btn-danger">Confirmar cancelación</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>
@endsection