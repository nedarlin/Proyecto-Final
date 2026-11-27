@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="mb-0">Odontólogos</h2>
        <a href="{{ route('admin.odontologos.crear') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo odontólogo
        </a>
    </div>

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white shadow-sm">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Colegiatura</th>
                    <th>Especialidad</th>
                    <th>Correo</th>
                    <th class="text-center">Citas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($odontologos as $o)
                    <tr>
                        <td>{{ $o->nombres }}</td>
                        <td>{{ $o->colegiatura }}</td>
                        <td>{{ $o->especialidad }}</td>
                        <td>{{ $o->correo }}</td>
                        <td class="text-center">{{ $o->citas_count }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.odontologos.editar', $o->id_odontologo) }}"
                                   class="btn btn-sm btn-outline-primary">Editar</a>
                                <form method="POST" action="{{ route('admin.odontologos.eliminar', $o->id_odontologo) }}"
                                      onsubmit="return confirm('¿Eliminar a {{ $o->nombres }}?');">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
