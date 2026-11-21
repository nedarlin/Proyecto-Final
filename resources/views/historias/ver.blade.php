@extends('layouts.app')

@section('content')
<div class="container py-4">
    <a href="{{ route('historias.index') }}" class="text-decoration-none">&larr; Volver a la búsqueda</a>

    <h2 class="mt-3 mb-4">Historia clínica {{ $historia->numero_hc }}</h2>

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif
}
    <div class="card card-body shadow-sm mb-4">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1"><strong>Paciente:</strong> {{ $historia->paciente->nombres }} {{ $historia->paciente->apellidos }}</p>
                <p class="mb-1"><strong>DNI:</strong> {{ $historia->paciente->dni }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>N.° HC:</strong> {{ $historia->numero_hc }}</p>
                <p class="mb-1"><strong>Fecha de apertura:</strong> {{ date('d/m/Y', strtotime($historia->fecha_apertura)) }}</p>
            </div>
        </div>
    </div>
    <div class="card card-body shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Observaciones generales</h5>
            <a href="{{ route('historias.editar', $historia->id_historia) }}" class="btn btn-sm btn-primary">Editar</a>
        </div>
        @if ($historia->observaciones)
            <p class="mb-0">{!! nl2br(e($historia->observaciones)) !!}</p>
        @else
            <p class="text-muted mb-0">Sin observaciones registradas.</p>
        @endif
    </div>

    <h5 class="mb-3">Evaluaciones</h5>
    @forelse ($evaluaciones as $evaluacion)
        <div class="card card-body shadow-sm mb-3">
            <p class="mb-2">
                <strong>{{ date('d/m/Y', strtotime($evaluacion->cita->fecha)) }}</strong>
                · {{ $evaluacion->cita->servicio->nombre }}
                · {{ $evaluacion->cita->odontologo->nombres }}
            </p>
            <p class="mb-1"><strong>Motivo de consulta:</strong> {{ $evaluacion->motivo_consulta }}</p>
            @if ($evaluacion->hallazgos_clinicos)
                <p class="mb-1"><strong>Hallazgos clínicos:</strong> {{ $evaluacion->hallazgos_clinicos }}</p>
            @endif
            <p class="mb-1"><strong>Diagnóstico:</strong> {{ $evaluacion->diagnostico }}</p>
            @if ($evaluacion->tratamiento_sugerido)
                <p class="mb-0"><strong>Tratamiento sugerido:</strong> {{ $evaluacion->tratamiento_sugerido }}</p>
            @endif
        </div>
    @empty
        <div class="alert alert-info">Este paciente aún no tiene evaluaciones registradas.</div>
    @endforelse
</div>
@endsection