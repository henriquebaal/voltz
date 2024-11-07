@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Editar Funcionário</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nome Completo</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $employee->name) }}" required>
        </div>

        <div class="form-group">
            <label for="position">Cargo</label>
            <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $employee->position) }}" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Telefone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" required>
        </div>

        <h5>Horários de Trabalho</h5>
        @foreach(['monday' => 'Segunda-feira', 'tuesday' => 'Terça-feira', 'wednesday' => 'Quarta-feira', 'thursday' => 'Quinta-feira', 'friday' => 'Sexta-feira', 'saturday' => 'Sábado', 'sunday' => 'Domingo'] as $day => $label)
            <div class="form-group">
                <label>{{ $label }}</label>
                <div class="form-row">
                    <div class="col">
                        <input type="time" name="work_schedule[{{ $day }}][start]" class="form-control" placeholder="Início" value="{{ old('work_schedule.'.$day.'.start', $employee->work_schedule[$day]['start'] ?? '') }}">
                    </div>
                    <div class="col">
                        <input type="time" name="work_schedule[{{ $day }}][end]" class="form-control" placeholder="Fim" value="{{ old('work_schedule.'.$day.'.end', $employee->work_schedule[$day]['end'] ?? '') }}">
                    </div>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
