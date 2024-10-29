<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <link rel="icon" href="{{ asset('simbolo.png') }}" type="image/png"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hamburgueria')</title>

    <!-- Incluir Bootstrap e Font Awesome para a navbar e ícones -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    

    <!-- Estilos personalizados -->
    <style>
    .navbar {
        margin-bottom: 0;
    }

    /* Botão do carrinho fixado no lado direito */
    .cart-button {
        position: fixed; /* Fixo no lado direito */
        bottom: 100px;
        right: 20px; /* Posição no lado direito */
        z-index: 1000; /* Para garantir que fique sobre o conteúdo */
        margin: 0;
    }

    /* Estilo básico para o submenu */
    .dropdown-submenu {
        position: relative;
    }

    /* Quando passar o mouse sobre o item de menu, mostrar o submenu à esquerda */
    .dropdown-submenu .dropdown-menu {
        display: none;
        top: 0;
        left: -200px; /* Ajustando o submenu para a esquerda */
        margin-top: -6px;
        border-radius: 0.25rem;
    }

    /* Exibir o submenu quando o mouse passa sobre o item de menu */
    .dropdown-submenu:hover .dropdown-menu {
        display: block;
    }


    /* Ajustando a largura do submenu para melhorar a aparência */
    .dropdown-menu {
        width: 200px;
    }
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .star-rating input[type="radio"] {
        display: none; /* Esconder os botões de rádio */
    }

    .star-rating label {
        font-size: 2em;
        color: #ddd;
        cursor: pointer;
    }

    .star-rating input[type="radio"]:checked ~ label {
        color: #ffc107; /* Cor das estrelas selecionadas */
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107; /* Cor das estrelas ao passar o mouse */
}
    .pagination .page-link {
        font-size: 1rem; /* Define o tamanho padrão do texto */
        padding: 0.5rem 0.75rem; /* Ajusta o padding dos links */
}

    .page-item .page-link svg {
        width: 1rem; /* Define um tamanho menor para os ícones */
        height: 12rem;
}

    /* Outros estilos */

    /* Adicionar espaçamento inferior ao conteúdo da página */
    .container {
        padding-bottom: 50px; /* Adiciona um espaçamento inferior */
    }



</style>

    @yield('styles')
</head>
<body>
    <!-- Navbar Global -->
    @include('layouts.navbar')

    <!-- Exibir o botão de carrinho apenas na home -->
@if(Route::currentRouteName() == 'home')
    <a href="{{ route('cart.show') }}" class="btn btn-success cart-button">
        <i class="fas fa-shopping-cart"></i> Carrinho 
        <span class="badge badge-light" id="cart-count">
            {{ session('cart') ? count(session('cart')) : 0 }}
        </span>
    </a>
@endif


    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        @yield('content')
    </div>

    <!-- Scripts do Bootstrap e JQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @yield('scripts')
</body>
</html>
