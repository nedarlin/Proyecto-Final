@extends('layouts.app')

@section('content')
<h2 class="text-center mb-4">Catalogo de servicios</h2>
<div class="row">
    @foreach ($servicios as $servicio)
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $servicio['nombre'] }}</h5>
                    <p class="card-text">{{ $servicio['descripcion'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection