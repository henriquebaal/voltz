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
                                    Pedido #{{ $order->order_number }} - Feito por {{ $order->user->name }} - {{ $order->created_at->format('d/m/Y H:i') }}

                                    <!-- Formulário para atualizar o status -->
                                    <form class="update-status-form mt-2" data-order-id="{{ $order->id }}">
                                        @csrf
                                        

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

<!-- Modal de Atualização -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Atualizando status...</h5>
            </div>
            <div class="modal-body">
                Aguarde enquanto o status está sendo atualizado.
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Sucesso</h5>
            </div>
            <div class="modal-body">
                Status atualizado com sucesso!
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.update-status-form').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let orderId = form.data('order-id');
        let status = form.find('select[name="status"]').val();

        // Abrir a modal de atualização
        $('#statusModal').modal('show');
        $('#statusModalLabel').text('Atualizando status...');
        $('.modal-body').text('Aguarde enquanto o status está sendo atualizado.');

        // Enviar a requisição com axios.post
        axios.post(`/admin/orders/${orderId}/status`, {
            status: status
        }, {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .then(function(response) {
            console.log('Resposta do servidor:', response.data.message);

            $('#statusModalLabel').text('Sucesso');
            $('.modal-body').text('Status atualizado com sucesso!');

            setTimeout(function() {
                $('#statusModal').modal('hide');
                location.reload();
            }, 2000);
        })
        .catch(function(error) {
            console.error('Erro ao atualizar o status:', error.response || error);
            $('.modal-body').text('Erro ao atualizar o status. Tente novamente.');

            $('#statusModalLabel').text('Erro');

            setTimeout(function() {
                $('#statusModal').modal('hide');
            }, 2000);
        });
    });
});


</script>
@endsection
