@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Historial de consultas</h2>
        <div class="d-flex flex-wrap justify-content-around">
            @foreach ($histories as $log)
                <div class="card" style="width: 18rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> Ciudad: {{ $log->city->name }} </li>
                        <li class="list-group-item"> Humedad: {{ $log->humidity }}% </li>
                        <li class="list-group-item"> Fecha: {{ $log->created_at }} </li>
                    </ul>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="mx-auto">
                {{$histories->links()}}
            </div>
        </div>
    </div>
@endsection
