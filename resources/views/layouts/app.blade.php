<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" href="{{ asset('simbolo.png') }}" type="image/png"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hamburgueria')</title>

    <!-- Incluir Bootstrap e Font Awesome para a navbar e ícones -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Estilos personalizados -->
    <style>
/* Estilos para dispositivos móveis */
@media (max-width: 767.98px) {
    .dropdown-menu {
        position: static;
        width: 100%;
        padding: 0;
        margin: 0;
        border: none;
        box-shadow: none;
    }
    
    .dropdown-submenu {
        position: relative;
    }

    /* Configuração para abrir o submenu à direita */
    .dropdown-submenu .dropdown-menu {
        position: absolute;
        top: 0;
        left: 100%; /* Abre à direita do item pai */
        display: none;
        min-width: 180px;
        padding: 0.5rem;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        z-index: 1000;
    }

    /* Exibir o submenu ao passar o mouse ou ao abrir no mobile */
    .dropdown-submenu:hover > .dropdown-menu,
    .dropdown-submenu:focus > .dropdown-menu,
    .dropdown-submenu > .dropdown-menu.show {
        display: block;
    }
}



        /* Configuração da Navbar */
        .navbar {
            margin-bottom: 0;
        }

        /* Botão do carrinho fixado no lado direito */
        .cart-button {
            position: fixed;
            bottom: 100px;
            right: 20px;
            z-index: 1000;
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
            left: -200px;
            margin-top: -6px;
            border-radius: 0.25rem;
        }

        .dropdown-submenu:hover .dropdown-menu {
            display: block;
        }

        /* Ajustando a largura do submenu */
        .dropdown-menu {
            width: 200px;
        }

        /* Estilos de avaliação com estrelas */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 2em;
            color: #ddd;
            cursor: pointer;
        }

        .star-rating input[type="radio"]:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }

        /* Estilos de paginação */
        .pagination .page-link {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
        }

        .page-item .page-link svg {
            width: 1rem;
            height: 1rem;
        }

        /* Adicionar espaçamento inferior ao conteúdo da página */
        .container {
            padding-bottom: 50px;
        }

        /* Importando a fonte RedHatDisplay com variantes */
        @font-face {
            font-family: 'RedHatDisplay';
            src: url('{{ asset('fonts/RedHatDisplay-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'RedHatDisplay';
            src: url('{{ asset('fonts/RedHatDisplay-Italic.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: italic;
        }

        @font-face {
            font-family: 'RedHatDisplay';
            src: url('{{ asset('fonts/RedHatDisplay-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: normal;
        }

        @font-face {
            font-family: 'RedHatDisplay';
            src: url('{{ asset('fonts/RedHatDisplay-BoldItalic.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: italic;
        }

        @font-face {
            font-family: 'RedHatDisplay';
            src: url('{{ asset('fonts/RedHatDisplay-Black.ttf') }}') format('truetype');
            font-weight: 900;
            font-style: normal;
        }

        @font-face {
            font-family: 'RedHatDisplay';
            src: url('{{ asset('fonts/RedHatDisplay-BlackItalic.ttf') }}') format('truetype');
            font-weight: 900;
            font-style: italic;
        }

        /* Adicionar outras variantes de fontes aqui conforme necessário */

        /* Aplicar a fonte personalizada em todo o site */
        body {
            font-family: 'RedHatDisplay', sans-serif;
        }

        @media (max-width: 768px) {
            .table tbody, .table thead {
             display: block;
            }

        .table thead {
             display: none; /* Esconde o cabeçalho em telas pequenas */
            }

        .table tbody tr {
            display: flex;
            flex-direction: column;
            margin-bottom: 1em;
            border: 1px solid #dee2e6;
            padding: 0.5em;
            }

        .table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.5em 0;
            border: none;
            }

        .table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            text-transform: capitalize;
            width: 50%;
            }
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
