@extends('layouts.app')

@section('title', 'Cardápio - Hamburgueria')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Nosso Cardápio</h1>

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

    <!-- Início da linha dos cards -->
    <div class="row">
        @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex align-items-stretch">
                <div class="card w-100">
                    <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text"><strong>Preço: </strong>R$ {{ number_format($product->price, 2, ',', '.') }}</p>

                        <!-- Botão para abrir a modal e selecionar os ingredientes -->
                        <button type="button" class="btn btn-primary btn-block" onclick="openIngredientModal({{ $product->id }})">Adicionar ao Carrinho</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Fim da linha dos cards -->
</div>

<!-- Modal para seleção de ingredientes -->
<div class="modal fade" id="ingredientModal" tabindex="-1" role="dialog" aria-labelledby="ingredientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ingredientModalLabel">Selecione os Ingredientes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ingredientForm">
                    @csrf
                    <input type="hidden" id="product_id" name="product_id">

                    <div class="form-group">
                        <label for="quantity">Quantidade</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="attributes">Ingredientes</label>
                        @if($attributes->isEmpty())
                            <p>Nenhum atributo cadastrado.</p>
                        @else
                            @foreach($attributes as $attribute)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="attributes[]" value="{{ $attribute->id }}" id="attribute-{{ $attribute->id }}">
                                    <label class="form-check-label" for="attribute-{{ $attribute->id }}">{{ $attribute->name }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="addToCartButton">Adicionar ao Carrinho</button>
            </div>
        </div>
    </div>
</div>

<!-- Axios Script -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function openIngredientModal(productId) {
    // Resetar todos os checkboxes de atributos
    const checkboxes = document.querySelectorAll('#ingredientForm .form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false; // Desmarca todos os checkboxes
    });

    // Resetar a quantidade para 1
    document.getElementById('quantity').value = 1;

    // Passar o ID do produto para o campo oculto e abrir a modal
    document.getElementById('product_id').value = productId;
    $('#ingredientModal').modal('show');
}


    document.getElementById('addToCartButton').addEventListener('click', function () {
    // Obter os dados do formulário
    const formData = new FormData(document.getElementById('ingredientForm'));

    axios.post("{{ route('cart.add') }}", formData)
        .then(response => {
            // Criar o alerta de sucesso
            const successMessage = document.createElement('div');
            successMessage.classList.add('alert', 'alert-success');
            successMessage.innerText = response.data.message;

            // Adicionar o alerta logo após o conteúdo do container principal (abaixo do navbar)
            const container = document.querySelector('.container'); // Selecionar o container principal
            container.prepend(successMessage); // Adicionar o alerta abaixo do navbar

            // Atualizar o número de itens no ícone do carrinho
            updateCartIcon(response.data.cartCount);

            // Fechar a modal
            $('#ingredientModal').modal('hide');

            // Remover a mensagem após 3 segundos
            setTimeout(() => {
                successMessage.remove();
            }, 3000);
        })
        .catch(error => {
            // Exibir mensagem de erro
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('alert', 'alert-danger');
            errorMessage.innerText = 'Erro ao adicionar o produto ao carrinho.';

            const container = document.querySelector('.container'); // Selecionar o container principal
            container.prepend(errorMessage); // Adicionar o alerta abaixo do navbar

            // Remover a mensagem após 3 segundos
            setTimeout(() => {
                errorMessage.remove();
            }, 3000);
        });
});


// Função para atualizar o ícone do carrinho com a nova quantidade
function updateCartIcon(cartCount) {
    const cartCountElement = document.getElementById('cart-count');
    cartCountElement.innerHTML = cartCount;  // Atualizar o valor do span com a nova quantidade
}


</script>
@endsection
