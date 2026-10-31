@extends('layouts.app')

@section('content')

<section class="hero text-center">
    <div class="container">
        <h1>Tu sonrisa, en buenas manos</h1>
        <p class="lead mt-3 mb-4">
            Conoce nuestros servicios y reserva tu cita odontologica en linea.
        </p>
        @if (session()->has('paciente_id'))
            <a href="#servicios" class="btn btn-primary px-4">Ver servicios</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary px-4">Reservar cita</a>
            <a href="#servicios" class="btn btn-outline-secondary px-4">Ver servicios</a>
        @endif
    </div>
</section>

<section id="servicios" class="py-5">
    <div class="container">
        <h2 class="titulo-seccion text-center">Nuestros servicios</h2>
        <div class="row g-4">
            @foreach ($servicios as $servicio)
                <div class="col-md-6 col-lg-3">
                    <div class="servicio h-100">
                        <h5>{{ $servicio['nombre'] }}</h5>
                        <p>{{ $servicio['descripcion'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="titulo-seccion text-center">Reserva en 3 pasos</h2>
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="paso-numero">1</div>
                <h5>Crea tu cuenta</h5>
                <p class="text-muted">Registrate con tus datos personales.</p>
            </div>
            <div class="col-md-4">
                <div class="paso-numero">2</div>
                <h5>Elige tu horario</h5>
                <p class="text-muted">Selecciona especialidad, fecha y hora disponible.</p>
            </div>
            <div class="col-md-4">
                <div class="paso-numero">3</div>
                <h5>Asiste a tu cita</h5>
                <p class="text-muted">Te atendemos en el horario que reservaste.</p>
            </div>
        </div>
    </div>
</section>

@if (!session()->has('paciente_id'))
<section class="franja text-center">
    <div class="container">
        <h3 class="fw-light mb-3">¿Listo para tu primera evaluacion?</h3>
        <a href="{{ route('registro') }}" class="btn btn-primary px-4">Crear cuenta</a>
    </div>
</section>
@endif

@endsection