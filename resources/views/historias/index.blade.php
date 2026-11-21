@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Historias clínicas</h2>

    <form method="GET" action="{{ route('historias.index') }}" class="card card-body shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-9">
                <label class="form-label">Buscar paciente por DNI o nombre</label>
                <input type="text" name="q" value="{{ $q }}" class="form-control" required>
            </div>
            <div class="col-12 col-md-3 d-grid">
                <button class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    @if ($pacientes !== null)
        @if ($pacientes->isEmpty())
            <div class="alert alert-info">No se encontraron pacientes. Intenta con otra búsqueda.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>DNI</th>
                            <th>N.° HC</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pacientes as $paciente)
                            <tr>
                                <td>{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
                                <td>{{ $paciente->dni }}</td>
                                <td>{{ $paciente->historiaClinica->numero_hc }}</td>
                                <td>
                                    <a href="{{ route('historias.ver', $paciente->historiaClinica->id_historia) }}"
                                       class="btn btn-sm btn-outline-primary">Ver historia</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
@endsection