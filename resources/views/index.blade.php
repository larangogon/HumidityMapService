@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Ciudades Disponibles</h2>
        <div class="d-flex flex-wrap justify-content-around">
            @foreach ($cities as $city)
                <div class="card" style="width: 18rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> Nombre: {{ $city->name }} </li>
                        <li class="list-group-item"> Latitud: {{ $city->lat }} </li>
                        <li class="list-group-item"> Longitud: {{ $city->lon }} </li>
                    </ul>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endsection
