@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Resumo do Pedido</h1>

    <!-- Mensagem de sucesso, se houver -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Número do Pedido: {{ $order->order_number }}</h5>
        </div>
        <div class="card-body">
            <p><strong>Data do Pedido:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Status do Pedido:</strong> {{ $order->status }}</p>
            <p><strong>Método de Pagamento:</strong> {{ $order->payment_method }}</p>
            <p><strong>Endereço de Entrega:</strong> {{ $order->delivery_address }}</p>
            
            <hr>
            <h5>Itens do Pedido:</h5>
            <ul class="list-group">
                @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <!-- Exibe o nome do produto -->
                            {{ $item->item_name }} - Quantidade: {{ $item->quantity }}
                            <!-- Exibe os atributos (ingredientes), se houver -->
                            @if(!empty($item->attributes) && $item->attributes->count() > 0)
                                <ul>
                                    @foreach($item->attributes as $attribute)
                                        <li><em>{{ $attribute->name }}</em></li>
                                    @endforeach
                                </ul>
                            @else
                                <em>Sem ingredientes adicionais</em>
                            @endif
                        </div>
                        <span>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-footer text-right">
            <h5><strong>Total: R$ {{ number_format($order->total, 2, ',', '.') }}</strong></h5>
        </div>
    </div>

    <!-- Botões para navegação -->
    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary">Voltar ao Cardápio</a>
        <a href="{{ route('orders.user') }}" class="btn btn-secondary ml-2">Meus Pedidos</a> <!-- Botão para Meus Pedidos -->
    </div>
</div>
@endsection
