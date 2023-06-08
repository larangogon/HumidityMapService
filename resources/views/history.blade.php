@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Historial de consultas</h2>
        <ul class="list-group">
            @foreach ($histories as $log)
                <li class="list-group-item">
                    <p class="mb-1">Ciudad: {{ $log->city->name }}</p>
                    <p class="mb-1">Humedad: {{ $log->humidity }}%</p>
                    <p class="mb-1">Fecha: {{ $log->created_at }}</p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
