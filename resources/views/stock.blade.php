@extends('layouts.app')

@section('title', 'Estoque - Hamburgueria')

@section('content')
    <h1 class="text-center mb-5">Gerenciamento de Estoque</h1>

    <div class="text-right mb-4">
        <a href="{{ route('stock.create') }}" class="btn btn-success">Incluir Novo Item</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Quantidade em Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <!-- Formulário para adicionar mais itens ao estoque -->
                        <form action="{{ route('stock.add', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" class="form-control" placeholder="Quantidade" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Adicionar Estoque</button>
                                </div>
                            </div>
                        </form>

                        <!-- Formulário para remover itens do estoque -->
                        <form action="{{ route('stock.remove', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" class="form-control" placeholder="Quantidade" required>
                                <div class="input-group-append">
                                    <button class="btn btn-warning" type="submit">Remover Estoque</button>
                                </div>
                            </div>
                        </form>

                        <!-- Botão que abre o modal de confirmação para remover o produto -->
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal{{ $product->id }}">
                            Remover Produto
                        </button>

                        <!-- Modal de confirmação para remover o produto -->
                        <div class="modal fade" id="confirmDeleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $product->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel{{ $product->id }}">Confirmar Remoção</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza de que deseja remover o produto <strong>{{ $product->name }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

                                        <!-- Formulário para remover o item do estoque -->
                                        <form action="{{ route('stock.destroy', $product->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Confirmar Remoção</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
