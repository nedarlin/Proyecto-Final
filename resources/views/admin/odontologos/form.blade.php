@extends('layouts.app')

@section('content')
@php
    $editando = $odontologo !== null;
    $accion = $editando
        ? route('admin.odontologos.actualizar', $odontologo->id_odontologo)
        : route('admin.odontologos.guardar');
@endphp
<div class="container py-4" style="max-width: 720px;">
    <h2 class="mb-4">{{ $editando ? 'Editar odontólogo' : 'Nuevo odontólogo' }}</h2>

    <form method="POST" action="{{ $accion }}" class="card card-body shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombres</label>
            <input type="text" name="nombres" maxlength="80"
                   value="{{ old('nombres', $odontologo->nombres ?? '') }}"
                   class="form-control @error('nombres') is-invalid @enderror">
            @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-md-5">
                <label class="form-label">Colegiatura</label>
                <input type="text" name="colegiatura" maxlength="10" placeholder="COP-10005"
                       value="{{ old('colegiatura', $odontologo->colegiatura ?? '') }}"
                       class="form-control @error('colegiatura') is-invalid @enderror">
                @error('colegiatura') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-7">
                <label class="form-label">Especialidad</label>
                <select name="especialidad" class="form-select @error('especialidad') is-invalid @enderror">
                    <option value="">Selecciona...</option>
                    @foreach ($especialidades as $esp)
                        <option value="{{ $esp }}" @selected(old('especialidad', $odontologo->especialidad ?? '') === $esp)>{{ $esp }}</option>
                    @endforeach
                </select>
                @error('especialidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" maxlength="100"
                   value="{{ old('correo', $odontologo->correo ?? '') }}"
                   class="form-control @error('correo') is-invalid @enderror">
            @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Contraseña</label>
            <input type="password" name="clave" maxlength="50" autocomplete="new-password"
                   class="form-control @error('clave') is-invalid @enderror">
            <div class="form-text">
                {{ $editando ? 'Déjala vacía si no quieres cambiarla.' : 'Mínimo 8 caracteres.' }}
            </div>
            @error('clave') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">{{ $editando ? 'Guardar cambios' : 'Registrar odontólogo' }}</button>
            <a href="{{ route('admin.odontologos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
