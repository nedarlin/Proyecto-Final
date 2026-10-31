@extends('layouts.app')

@section('content')
<div class="container flex-grow-1 d-flex align-items-center py-4">
    <div class="row justify-content-center w-100">
        <div class="col-md-7">
            <h2 class="text-center mb-4">Crear cuenta</h2>
        <form method="POST" action="{{ route('registro.guardar') }}" class="card card-body shadow">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombres</label>
                    <input type="text" name="nombres" value="{{ old('nombres') }}"
                           class="form-control @error('nombres') is-invalid @enderror">
                    @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                           class="form-control @error('apellidos') is-invalid @enderror">
                    @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">DNI</label>
                    <input type="text" name="dni" value="{{ old('dni') }}"
                           class="form-control @error('dni') is-invalid @enderror">
                    @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Celular</label>
                    <input type="text" name="celular" value="{{ old('celular') }}"
                           class="form-control @error('celular') is-invalid @enderror">
                    @error('celular') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo electronico</label>
                <input type="email" name="correo" value="{{ old('correo') }}"
                       class="form-control @error('correo') is-invalid @enderror">
                @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña (minimo 8 caracteres)</label>
                <input type="password" name="clave"
                       class="form-control @error('clave') is-invalid @enderror">
                @error('clave') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="terminos" class="form-check-input @error('terminos') is-invalid @enderror">
                <label class="form-check-label">Acepto los terminos y la politica de privacidad</label>
                @error('terminos') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary w-100">Registrarme</button>
        </form>
    </div>
</div>
@endsection