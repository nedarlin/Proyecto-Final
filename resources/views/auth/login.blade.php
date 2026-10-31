@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h2 class="text-center">Iniciar sesion</h2>

        @if (session('exito'))
            <div class="alert alert-success">{{ session('exito') }}</div>
        @endif
        @error('correo')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('login.entrar') }}" class="card card-body shadow">
            @csrf
            <div class="mb-3">
                <label class="form-label">Correo electronico</label>
                <input type="email" name="correo" value="{{ old('correo') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="clave" class="form-control">
            </div>
            <button class="btn btn-primary w-100">Ingresar</button>
            <p class="text-center mt-3 mb-0">¿No tienes cuenta? <a href="{{ route('registro') }}">Registrate</a></p>
        </form>
    </div>
</div>
@endsection