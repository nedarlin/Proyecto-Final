@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <a href="{{ route('catalogo') }}" class="text-decoration-none">&larr; Volver al catálogo</a>

    <div class="card card-body shadow-sm mt-3">
        <div class="row g-4 align-items-center">

            @if ($servicio->imagen())
                <div class="col-md-5">
                    <img src="{{ $servicio->imagen() }}" alt="{{ $servicio->nombre }}"
                         class="img-fluid rounded w-100">
                </div>
            @endif

            <div class="{{ $servicio->imagen() ? 'col-md-7' : 'col-12' }}">
                <h2 class="mb-3">{{ $servicio->nombre }}</h2>
                <p>{{ $servicio->descripcion }}</p>

                <p class="mb-2"><strong>Especialidad:</strong> {{ $servicio->especialidad }}</p>

                <p class="mb-1"><strong>Odontólogos que lo atienden:</strong></p>
                <ul>
                    @forelse ($odontologos as $odontologo)
                        <li>{{ $odontologo->nombres }}</li>
                    @empty
                        <li class="text-muted">Aún no hay odontólogos asignados.</li>
                    @endforelse
                </ul>
                <a href="{{ route('citas.agendar', ['servicio' => $servicio->id_servicio]) }}"
                   class="btn btn-primary">Agendar cita</a>
            </div>
        </div>
    </div>
</div>
@endsection