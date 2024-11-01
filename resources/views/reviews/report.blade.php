@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Relatório de Avaliações</h1>

    <!-- Filtros para o relatório -->
    <form action="{{ route('reviews.report') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="customer_id">Cliente:</label>
                <select name="customer_id" id="customer_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="stars">Estrelas:</label>
                <select name="stars" id="stars" class="form-control">
                    <option value="">Todas</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('stars') == $i ? 'selected' : '' }}>
                            {{ $i }} {{ $i > 1 ? 'Estrelas' : 'Estrela' }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <label for="sort_order">Ordenar por:</label>
                <select name="sort_order" id="sort_order" class="form-control">
                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Mais recente</option>
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Menos recente</option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabela de Avaliações -->
    @if($reviews->isEmpty())
        <p class="text-center">Nenhuma avaliação encontrada.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Número do Pedido</th>
                    <th>Avaliação</th>
                    <th>Comentário</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $review->user->name }}</td>
                        <td>
                            <a href="{{ route('orders.summary', $review->id) }}">
                                {{ $review->order_number }}
                            </a>
                        </td>
                        <td>{{ $review->rating }} Estrela(s)</td>
                        <td>{{ $review->review_comment ?? 'Nenhum comentário' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
