@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Painel de Controle do Administrador</h1>

    <!-- Primeira linha com Total de Pedidos e Total de Usuários -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header">
                    <h5>Total de Pedidos</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header">
                    <h5>Total de Usuários</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda linha com Pedidos Recentes paginados -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5>Pedidos Recentes</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <ul class="list-group">
                            @foreach($recentOrders as $order)
                                <li class="list-group-item">
                                    <!-- Exibindo o número do pedido e o nome do cliente -->
                                    Pedido #{{ $order->order_number }} - Feito por {{ $order->user->name }} - {{ $order->created_at->format('d/m/Y H:i') }}

                                    <!-- Formulário para atualizar o status -->
                                    <form action="{{ route('admin.updateOrderStatus', $order->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select name="status" class="form-control">
                                                <option value="Pendente" {{ $order->status == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                                <option value="Em preparo" {{ $order->status == 'Em preparo' ? 'selected' : '' }}>Em preparo</option>
                                                <option value="Saiu para entrega" {{ $order->status == 'Saiu para entrega' ? 'selected' : '' }}>Saiu para entrega</option>
                                                <option value="Entregue" {{ $order->status == 'Entregue' ? 'selected' : '' }}>Entregue</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">Atualizar Status</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Links de paginação para pedidos recentes -->
                        <div class="mt-3 d-flex justify-content-center">
                        {{ $recentOrders->links('pagination::bootstrap-4') }}

                        </div>
                    @else
                        <p>Nenhum pedido recente.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
