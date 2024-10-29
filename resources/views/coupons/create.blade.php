@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cadastrar Novo Cupom</h1>

    <!-- Mensagens de Sucesso e Erro -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Código do Cupom</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="discount">Desconto (%)</label>
            <input type="number" name="discount" id="discount" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="valid_from">Válido a partir de</label>
            <input type="datetime-local" name="valid_from" id="valid_from" class="form-control">
        </div>

        <div class="form-group">
            <label for="valid_until">Válido até</label>
            <input type="datetime-local" name="valid_until" id="valid_until" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Cadastrar Cupom</button>
        <a href="{{ route('coupons.index') }}" class="btn btn-secondary mt-3">Ver Cupons Ativos</a>
    </form>
</div>
@endsection
