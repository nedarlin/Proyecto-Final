@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Agendar cita odontológica</h2>

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

    <form method="GET" action="{{ route('citas.agendar') }}" class="card card-body shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label">1. Especialidad</label>
                <select name="especialidad" class="form-select" required>
                    <option value="">Selecciona...</option>
                    @foreach ($especialidades as $item)
                        <option value="{{ $item }}" @selected($item === $especialidad)>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">2. Fecha</label>
                <input type="date" name="fecha" value="{{ $fecha }}" class="form-control" required
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>
            <div class="col-12 col-md-3 d-grid">
                <button class="btn btn-outline-primary">Ver horarios</button>
            </div>
        </div>

        @if ($odontologo)
            <p class="mb-0 mt-3 text-muted">
                Odontólogo: <strong>{{ $odontologo['nombre'] }} · {{ $odontologo['especialidad'] }}</strong>
            </p>
        @endif
    </form>

    @if ($mostrarHorarios)
        <form method="POST" action="{{ route('citas.confirmar') }}">
            @csrf
            <input type="hidden" name="especialidad" value="{{ $especialidad }}">
            <input type="hidden" name="fecha" value="{{ $fecha }}">

            <div class="row g-4">
                <div class="col-lg-8">
                    <h5 class="mb-3">3. Horarios del {{ date('d/m/Y', strtotime($fecha)) }}</h5>

                    @if (!$hayDisponibles)
                        <div class="alert alert-warning">
                            No hay horarios disponibles para esta fecha. Elige otra fecha.
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($horarios as $h)
                            <input type="radio" class="btn-check" name="hora"
                                   id="hora-{{ $loop->index }}" value="{{ $h['hora'] }}"
                                   @disabled($h['ocupado'])>
                            <label class="btn {{ $h['ocupado'] ? 'btn-outline-secondary' : 'btn-outline-primary' }}"
                                   for="hora-{{ $loop->index }}">
                                {{ $h['hora'] }}
                                @if ($h['ocupado']) <small>(ocupado)</small> @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-body shadow-sm">
                        <h5>Resumen de la cita</h5>
                        <ul class="list-unstyled mb-3">
                            <li><strong>Especialidad:</strong> {{ $especialidad }}</li>
                            <li><strong>Odontólogo:</strong> {{ $odontologo['nombre'] }}</li>
                            <li><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($fecha)) }}</li>
                            <li><strong>Hora:</strong> <span id="resumen-hora">Sin seleccionar</span></li>
                        </ul>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary">Confirmar cita</button>
                            <a href="{{ route('inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

<script src="{{ asset('js/agendar.js') }}"></script>
@endsection