@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Carrinho de Compras</h1>


    @if(session('cart') && count(session('cart')) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Atributos</th> <!-- Adicionado para exibir os atributos -->
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;  // Inicializa o total do pedido
                @endphp
                @foreach($cart as $id => $item)
                    @php
                        $itemTotal = $item['price'] * $item['quantity'];  // Calcula o total por item
                        $total += $itemTotal;  // Acumula o valor total do pedido
                    @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>

                        <!-- Exibir os atributos do produto -->
                        <td>
                            @if(!empty($item['attributes']) && $item['attributes']->count() > 0)
                                <ul>
                                    @foreach($item['attributes'] as $attribute)
                                        <li>{{ $attribute->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <em>Nenhum atributo selecionado</em>
                            @endif
                        </td>

                        <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                        <td>
                            <!-- Formulário para alterar a quantidade -->
                            <form action="{{ route('cart.update', $id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 70px; display: inline-block;">
                                <button type="submit" class="btn btn-info btn-sm">Atualizar</button>
                            </form>
                        </td>
                        <td>R$ {{ number_format($itemTotal, 2, ',', '.') }}</td>
                        <td>
                            <!-- Formulário para remover o item -->
                            <form action="{{ route('cart.remove', $id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Exibir o total do pedido -->
        <div class="text-right">
            <h4><strong>Total do Pedido: R$ {{ number_format($total, 2, ',', '.') }}</strong></h4>
        </div>

        <!-- Botão para Revisar Dados -->
        <div class="text-center mt-5">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reviewModal">
                Revisar Dados de Entrega e Pagamento
            </button>
        </div>

    @else
        <p class="text-center">Seu carrinho está vazio!</p>
    @endif

    <div class="text-center mt-5">
        <a href="{{ route('home') }}" class="btn btn-secondary">Voltar ao Cardápio</a>
    </div>
</div>

<!-- Modal de Revisão de Dados -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Revisar Dados de Entrega e Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <!-- Dados do Cliente -->
                <h5>Dados do Cliente</h5>
                <div>
                    <strong>Nome: </strong>{{ Auth::user()->name }}
                </div>
                <div class="mt-2">
                    <strong>Email: </strong>{{ Auth::user()->email }}
                </div>

                <!-- Exibir os produtos e atributos selecionados -->
                <h5>Itens do Pedido:</h5>

                @php
                    $cart = session('cart', []);
                @endphp

                @if(!empty($cart) && count($cart) > 0)
                    <ul class="list-group">
                        @foreach($cart as $item)
                            <li class="list-group-item">
                                {{ $item['name'] }} - Quantidade: {{ $item['quantity'] }}
                                <ul>
                                    @if(!empty($item['attributes']) && count($item['attributes']) > 0)
                                        @foreach($item['attributes'] as $attribute)
                                            <li>{{ $attribute->name }}</li>
                                        @endforeach
                                    @else
                                        <li><em>Nenhum atributo selecionado</em></li>
                                    @endif
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center">Seu carrinho está vazio!</p>
                @endif

                            
                <!-- Formulário para Editar Endereço e Telefone -->
                <form id="updateUserDataForm">
                    @csrf
                    <div class="mt-2">
                        <strong>Endereço de Entrega: </strong>
                        <span id="address">{{ Auth::user()->address ?? 'Endereço não informado' }}</span>
                        <a href="#" onclick="editField('address')" class="ml-2">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <input type="text" class="form-control mt-2 d-none" id="addressInput" name="address" value="{{ Auth::user()->address }}" placeholder="Digite seu endereço">
                    </div>
                    <div class="mt-2">
                        <strong>Telefone: </strong>
                        <span id="phone">{{ Auth::user()->phone ?? 'Telefone não informado' }}</span>
                        <a href="#" onclick="editField('phone')" class="ml-2">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <input type="text" class="form-control mt-2 d-none" id="phoneInput" name="phone" value="{{ Auth::user()->phone }}" placeholder="Digite seu telefone">
                    </div>

                    <!-- Botão de Salvar Alterações -->
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-info" onclick="saveUserData()">Salvar Alterações</button>
                    </div>

                    <!-- Mensagem de Sucesso Após o Botão Salvar -->
                    <div class="text-center mt-3" id="successMessage">
                    </div>
                </form>

                <!-- Seção de Pagamento -->
                <hr>
                <h5>Método de Pagamento</h5>
                <hr>
                <h5>Cupom de Desconto</h5>
                <form id="applyCouponForm">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="coupon_code" class="form-control" placeholder="Digite o código do cupom" >
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" onclick="applyCoupon()">Aplicar Cupom</button>
                    </div>
                </div>

                <!-- Mensagens de Sucesso ou Erro do Cupom -->
                    <div id="couponMessage" class="text-center"></div>
                </form>
                <hr>
                <h5>Método de Pagamento</h5>
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" name="total" value="{{ $total }}"> <!-- Campo oculto com o valor total -->
                    <div class="form-group">
                        <label for="paymentMethod">Selecione o Método de Pagamento</label>
                        <select class="form-control" id="paymentMethod" name="paymentMethod" required>
                            <option value="">Escolha uma opção</option>
                            <option value="credit_card">Cartão de Crédito</option>
                            <option value="debit_card">Cartão de Débito</option>
                            <option value="pix">PIX</option>
                            <option value="cash">Dinheiro na Entrega</option>
                        </select>
                    </div>

                    <!-- Exibir Total do Pedido na Modal -->
                    <div class="alert alert-info text-center">
                    <h5><strong>Total do Pedido: <span id="totalComDesconto">R$ {{ number_format($total, 2, ',', '.') }}</span></strong></h5>
                    </div>

                    <!-- Centralizar o botão Confirmar Pedido -->
                    <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-success" onclick="confirmOrder()">Confirmar Pedido</button>
                    </div>
                                    
                    <!-- Mensagem de Erro -->                        
                    <div class="text-center mt-3" id="errorMessage">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript com Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Função para ativar o modo de edição de um campo
    function editField(field) {
        const displaySpan = document.getElementById(field);
        const inputField = document.getElementById(`${field}Input`);

        // Alterna a exibição entre o texto e o campo de entrada
        if (displaySpan.classList.contains('d-none')) {
            displaySpan.classList.remove('d-none');
            inputField.classList.add('d-none');
        } else {
            displaySpan.classList.add('d-none');
            inputField.classList.remove('d-none');
        }
    }

    // Função para salvar os dados atualizados do usuário via Axios
    function saveUserData() {
        // Obter os dados atualizados do formulário
        const address = document.getElementById('addressInput').value.trim();
        const phone = document.getElementById('phoneInput').value.trim();

        // Verifica se os campos estão preenchidos corretamente
        if (!address || !phone) {
            document.getElementById('successMessage').innerHTML = '<div class="alert alert-danger">Por favor, preencha todos os campos.</div>';
            return;
        }

        // Realiza a requisição com axios
        axios.post('{{ route("user.updateAddressPhone") }}', {
            address: address,
            phone: phone,
            _token: '{{ csrf_token() }}'  // Passar o token CSRF para segurança
        })
        .then(response => {
            // Exibe a mensagem de sucesso
            document.getElementById('successMessage').innerHTML = '<div class="alert alert-success">Dados atualizados com sucesso!</div>';
            
            // Atualiza os campos de exibição na modal
            document.getElementById('address').textContent = address;
            document.getElementById('phone').textContent = phone;

            // Alternar a exibição dos campos para o modo de visualização
            editField('address');
            editField('phone');
        })
        .catch(error => {
            document.getElementById('successMessage').innerHTML = '<div class="alert alert-danger">Erro ao atualizar dados. Tente novamente.</div>';
        });
    }

    // Função para confirmar o pedido e enviar o formulário de pagamento via Axios
    function confirmOrder() {
    const paymentMethod = document.getElementById('paymentMethod').value;
    const address = document.getElementById('address').textContent;
    const phone = document.getElementById('phone').textContent;

    if (!address || address === 'Endereço não informado') {
        alert('Por favor, preencha o endereço de entrega antes de confirmar o pedido.');
        return;
    }

    if (!phone || phone === 'Telefone não informado') {
        alert('Por favor, preencha o número de telefone antes de confirmar o pedido.');
        return;
    }

    if (!paymentMethod) {
        alert('Por favor, selecione um método de pagamento.');
        return;
    }

    // Pegar o valor do total de forma correta do campo oculto
    const total = document.querySelector('input[name="total"]').value;

    // Realiza a requisição com axios para processar o pedido
    axios.post('{{ route("orders.store") }}', {
        total: total,
        paymentMethod: paymentMethod,
        _token: '{{ csrf_token() }}'  // Passar o token CSRF para segurança
    })
    .then(response => {
        // Redireciona para a página de resumo se o pedido for bem-sucedido
        window.location.href = response.data.redirect_url;
    })
    .catch(error => {
        if (error.response) {
            // Exibe a mensagem de erro se houver falta de estoque ou outro problema
            document.getElementById('errorMessage').innerHTML = `
                <div class="alert alert-danger">${error.response.data.message}</div>
            `;
        } else {
            // Exibe um erro genérico se a requisição falhar
            document.getElementById('errorMessage').innerHTML = `
                <div class="alert alert-danger">Ocorreu um erro ao processar seu pedido. Tente novamente.</div>
            `;
        }
    });
}

    function applyCoupon() {
        const couponCode = document.querySelector('input[name="coupon_code"]').value.trim();

        // Verifica se o campo do cupom está preenchido
        if (!couponCode) {
            document.getElementById('couponMessage').innerHTML = '<div class="alert alert-danger">Por favor, insira um código de cupom.</div>';
            return;
        }

        // Envia a requisição para aplicar o cupom
        axios.post('{{ route("cart.applyCoupon") }}', {
            coupon_code: couponCode,
            _token: '{{ csrf_token() }}'
        })
        .then(response => {
            // Exibe a mensagem de sucesso e o desconto aplicado
            document.getElementById('couponMessage').innerHTML = `<div class="alert alert-success">${response.data.success}</div>`;

            // Atualiza o total com o desconto aplicado
            const totalComDesconto = response.data.discountedTotal;
            document.getElementById('totalComDesconto').textContent = `R$ ${totalComDesconto.toFixed(2).replace('.', ',')}`;
        })
        .catch(error => {
            // Exibe a mensagem de erro caso o cupom seja inválido ou expirado
            if (error.response && error.response.data.error) {
                document.getElementById('couponMessage').innerHTML = `<div class="alert alert-danger">${error.response.data.error}</div>`;
            } else {
                document.getElementById('couponMessage').innerHTML = '<div class="alert alert-danger">Erro ao aplicar o cupom. Tente novamente.</div>';
            }
        });
    }
</script>

@endsection
