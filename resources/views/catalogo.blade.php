@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Catálogo de servicios</h2>

    @if ($servicios->isEmpty())
        <div class="card card-body text-center shadow-sm">
            <p class="mb-3">Por el momento no hay servicios disponibles.</p>
            <div><a href="{{ route('inicio') }}" class="btn btn-primary">Volver al inicio</a></div>
        </div>
    @else
        <div class="row g-4">
            @foreach ($servicios as $servicio)
                <div class="col-md-6 col-lg-3">
                    <div class="servicio h-100 d-flex flex-column">
                        @if ($servicio->imagen())
                            <img src="{{ $servicio->imagen() }}" alt="{{ $servicio->nombre }}" class="servicio-img" loading="lazy">
                        @endif
                        <h5>{{ $servicio->nombre }}</h5>
                        <p class="flex-grow-1">{{ $servicio->descripcion }}</p>
                        <a href="{{ route('catalogo.servicio', $servicio->id_servicio) }}"
                           class="btn btn-sm btn-outline-primary">Ver detalle</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection