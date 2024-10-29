@extends('layouts.app')

@section('title', 'Cadastro de Atributos')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Cadastro de Atributos</h1>

    <!-- Exibir mensagens de sucesso ou erro -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulário de cadastro de atributos -->
    <form action="{{ route('attributes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nome do Atributo</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block">Cadastrar Atributo</button>
    </form>
</div>
@endsection
