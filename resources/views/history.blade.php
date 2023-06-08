@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Historial de consultas</h2>
        <div class="d-flex flex-wrap justify-content-around py-2">
            @foreach ($histories as $log)
                <div class="card mb-4" style="width: 14rem;">
                    <div class="card-body">
                        <h5 class="card-title">Ciudad: {{ $log->city->name }} </h5>
                        <p class="card-text">Fecha: {{ $log->created_at }}</p>
                        <li class="list-group-item"> Humedad: {{ $log->humidity }}% </li>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="mx-auto" style="width: 300px;">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{ $histories->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>
@endsection
