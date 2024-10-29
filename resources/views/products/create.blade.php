@extends('layouts.app')

@section('title', 'Cadastrar Produto - Hamburgueria')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-5">Cadastrar Produto</h1>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Nome do Produto</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Preço</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="image">Imagem do Produto</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Cadastrar Produto</button>
        </form>

        <!-- Exibir erros de validação -->
        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
