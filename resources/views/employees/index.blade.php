@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Gestão de Funcionários</h1>

    <div class="text-right mb-4">
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Cadastrar Novo Funcionário</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($employees->isEmpty())
        <p class="text-center">Nenhum funcionário cadastrado.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Horário de Trabalho</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>
                            @if($employee->work_schedule)
                                @foreach($employee->work_schedule as $day => $schedule)
                                    <strong>
                                        @switch($day)
                                            @case('monday')
                                                Segunda-feira:
                                                @break
                                            @case('tuesday')
                                                Terça-feira:
                                                @break
                                            @case('wednesday')
                                                Quarta-feira:
                                                @break
                                            @case('thursday')
                                                Quinta-feira:
                                                @break
                                            @case('friday')
                                                Sexta-feira:
                                                @break
                                            @case('saturday')
                                                Sábado:
                                                @break
                                            @case('sunday')
                                                Domingo:
                                                @break
                                            @default
                                                {{ ucfirst($day) }}:
                                        @endswitch
                                    </strong>
                                    {{ $schedule['start'] }} - {{ $schedule['end'] }}<br>
                                @endforeach
                            @else
                                Não definido
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este funcionário?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
