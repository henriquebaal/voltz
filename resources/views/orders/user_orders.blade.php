@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Meus Pedidos</h1>

    <!-- Filtro para ordenar por data -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('orders.user') }}" method="GET">
                <div class="form-group">
                    <label for="sort">Ordenar por data:</label>
                    <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Mais recentes primeiro</option>
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Mais antigos primeiro</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($orders->isEmpty())
        <p class="text-center">Você ainda não tem pedidos cadastrados.</p>
    @else
        <div class="accordion" id="ordersAccordion">
            @foreach($orders as $order)
                <div class="card mb-3">
                    <div class="card-header" id="heading{{ $order->id }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $order->id }}" aria-expanded="true" aria-controls="collapse{{ $order->id }}">
                                {{ $order->created_at->format('d/m/Y H:i') }} - Pedido #{{ $order->order_number }} - Status: {{ $order->status }} - Total: R$ {{ number_format($order->total, 2, ',', '.') }}
                            </button>
                        </h5>
                    </div>

                    <div id="collapse{{ $order->id }}" class="collapse" aria-labelledby="heading{{ $order->id }}" data-parent="#ordersAccordion">
                        <div class="card-body">
                            <p><strong>Data do Pedido:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Método de Pagamento:</strong> {{ $order->payment_method }}</p>
                            <p><strong>Endereço de Entrega:</strong> {{ $order->delivery_address }}</p>
                            <hr>
                            <h5>Itens do Pedido:</h5>
                            <ul class="list-group">
                                @foreach($order->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $item->item_name }} - Quantidade: {{ $item->quantity }}
                                        <span>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</span>
                                    </li>
                                    
                                    <!-- Ingredientes associados ao item do pedido -->
                                    @if($item->attributes->isNotEmpty())
                                        <ul class="list-group mt-2">
                                            <li class="list-group-item list-group-item-light">
                                                <strong>Ingredientes:</strong>
                                            </li>
                                            @foreach($item->attributes as $attribute)
                                                <li class="list-group-item">
                                                    {{ $attribute->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-2">Sem ingredientes adicionais.</p>
                                    @endif
                                @endforeach
                            </ul>

                            <!-- Exibir o formulário de avaliação apenas se o status for "entregue" -->
                            <hr>
                            @if($order->status === 'Entregue')
                                @if(!$order->rating)
                                    <hr>
                                    <h5>Avaliar Pedido</h5>
                                    <form action="{{ route('orders.rate', $order->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Avaliação:</label>
                                            <div class="star-rating">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" id="star{{ $i }}-{{ $order->id }}" name="rating" value="{{ $i }}" required />
                                                    <label for="star{{ $i }}-{{ $order->id }}" title="{{ $i }} estrelas">
                                                        &#9733;
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="review_comment">Comentário:</label>
                                            <textarea name="review_comment" id="review_comment" class="form-control" rows="3" placeholder="Deixe seu comentário sobre o pedido"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Enviar Avaliação</button>
                                    </form>
                                @else
                                    <p><strong>Avaliação:</strong> {{ $order->rating }} estrelas</p>
                                    <p><strong>Comentário:</strong> {{ $order->review_comment }}</p>
                                @endif
                            @else
                                <p><em>Avaliação disponível apenas após a entrega do pedido.</em></p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="text-center mt-5">
        <a href="{{ route('home') }}" class="btn btn-primary">Voltar ao Cardápio</a>
    </div>
</div>
@endsection
