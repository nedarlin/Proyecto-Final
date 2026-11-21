@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Registrar evaluación y diagnóstico</h2>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-body shadow-sm">
                <h5>Paciente</h5>
                <ul class="list-unstyled mb-3">
                    <li><strong>Nombre:</strong> {{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</li>
                    <li><strong>DNI:</strong> {{ $cita->paciente->dni }}</li>
                </ul>
                <h5>Cita</h5>
                <ul class="list-unstyled mb-3">
                    <li><strong>Servicio:</strong> {{ $cita->servicio->nombre }}</li>
                    <li><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($cita->fecha)) }} · {{ substr($cita->hora, 0, 5) }}</li>
                    <li><strong>Estado:</strong> <span class="badge text-bg-warning">{{ $cita->estado }}</span></li>
                </ul>
                <h5>Historia clínica</h5>
                <ul class="list-unstyled mb-0">
                    <li><strong>N.° HC:</strong> {{ $cita->paciente->historiaClinica->numero_hc }}</li>
                    <li><strong>Última atención:</strong>
                        {{ $ultimaAtencion ? date('d/m/Y', strtotime($ultimaAtencion)) : 'Primera atención' }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-8">
            <form method="POST" action="{{ route('evaluaciones.guardar', $cita->id_cita) }}" class="card card-body shadow-sm">
                @csrf
                <h5 class="mb-3">Evaluación clínica</h5>

                <div class="mb-3">
                    <label class="form-label">Motivo de consulta *</label>
                    <input type="text" name="motivo_consulta" value="{{ old('motivo_consulta') }}" maxlength="200"
                           class="form-control @error('motivo_consulta') is-invalid @enderror">
                    @error('motivo_consulta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Hallazgos clínicos</label>
                    <textarea name="hallazgos_clinicos" rows="3" class="form-control">{{ old('hallazgos_clinicos') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Diagnóstico *</label>
                    <textarea name="diagnostico" rows="3"
                              class="form-control @error('diagnostico') is-invalid @enderror">{{ old('diagnostico') }}</textarea>
                    @error('diagnostico') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Tratamiento sugerido</label>
                    <input type="text" name="tratamiento_sugerido" value="{{ old('tratamiento_sugerido') }}" maxlength="200"
                           class="form-control">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Guardar evaluación</button>
                    <a href="{{ route('agenda', ['fecha' => $cita->fecha]) }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection