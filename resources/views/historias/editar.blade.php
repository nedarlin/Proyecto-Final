@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <h2 class="mb-1">Editar historia clínica</h2>
    <p class="text-muted mb-4">
        {{ $historia->numero_hc }} · {{ $historia->paciente->nombres }} {{ $historia->paciente->apellidos }}
    </p>

    <form method="POST" action="{{ route('historias.guardar', $historia->id_historia) }}" class="card card-body shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Observaciones generales</label>
            <textarea name="observaciones" rows="6" maxlength="3000"
                      class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $historia->observaciones) }}</textarea>
            @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">Guardar</button>
            <a href="{{ route('historias.ver', $historia->id_historia) }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection