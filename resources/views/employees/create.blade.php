@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Cadastrar Funcionário</h1>

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nome Completo</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="position">Cargo</label>
            <input type="text" name="position" id="position" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">Telefone</label>
            <input type="text" name="phone" id="phone" class="form-control">
        </div>

        <h5>Horários de Trabalho</h5>
        @foreach(['segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo'] as $day)
                    <div class="form-group">
                <label>{{ ucfirst($day) }}</label>
                <div class="form-row">
                    <div class="col">
                        <input type="time" name="work_schedule[{{ $day }}][start]" class="form-control" placeholder="Início">
                    </div>
                    <div class="col">
                        <input type="time" name="work_schedule[{{ $day }}][end]" class="form-control" placeholder="Fim">
                    </div>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success btn-block">Cadastrar Funcionário</button>
    </form>
</div>
@endsection
