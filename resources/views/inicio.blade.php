@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <h1>Bienvenido a Odonto Sonrisas</h1>
    <p class="lead">Agenda tus citas odontologicas en linea, sin depender de WhatsApp.</p>
    @if (session()->has('paciente_id'))
        <a href="{{ route('catalogo') }}" class="btn btn-primary">Ver catalogo de servicios</a>
    @else
        <a href="{{ route('registro') }}" class="btn btn-primary">Crear cuenta</a>
        <a href="{{ route('login') }}" class="btn btn-outline-primary">Iniciar sesion</a>
    @endif
</div>
@endsection