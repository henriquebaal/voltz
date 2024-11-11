<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('storage/logos/Logotipo.png') }}" alt="Logotipo" height="30">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <!-- Submenu "Minha Conta" com itens adicionais -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Minha Conta
                </a>
                <div class="dropdown-menu" aria-labelledby="accountDropdown">
                    <a class="dropdown-item" href="{{ route('account.show') }}">Perfil</a>


                    <!-- Submenu "Estoque" visível apenas para administradores -->
                    @if(Auth::user() && Auth::user()->is_admin)
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">Estoque</a>
                            <div class="dropdown-menu dropdown-left">
                                <a class="dropdown-item" href="{{ route('stock.index') }}">Gestão de Estoque</a>
                                <a class="dropdown-item" href="{{ route('stock.create') }}">Cadastro de Produto</a>
                                <a class="dropdown-item" href="{{ route('attributes.create') }}">Cadastro de Atributos</a>
                            </div>
                        </div>

                        <!-- Link "Relatório de Vendas" visível apenas para administradores -->
                        <div class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">Gerência</a>
                            <div class="dropdown-menu dropdown-left">
                                <a class="dropdown-item" href="{{ route('report.sales') }}">Vendas</a>
                                <a class="dropdown-item" href="{{ route('reviews.report') }}">Avaliações</a>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Painel</a>
                                <a class="dropdown-item" href="{{ route('employees.index') }}">Funcionários</a>
                            </div>
                        </div>

                        <!-- Link "Cadastro de Cupons" visível apenas para administradores -->
                        <div class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">Marketing</a>
                            <div class="dropdown-menu dropdown-left">
                                <a class="dropdown-item" href="{{ route('coupons.index') }}">Cupons</a>
                                <a class="dropdown-item" href="{{ route('reviews.report') }}">Avaliações</a>
                            </div>
                        </div>
                    @endif

                    <!-- Outros itens do submenu "Minha Conta" -->
                    <a class="dropdown-item" href="{{ route('orders.user') }}">Meus Pedidos</a>

                    <!-- Link de Logout dentro do submenu -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                         @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- Script para controlar submenus no mobile -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Controla o clique no item do submenu para abrir corretamente
    $('.dropdown-submenu > a').on("click", function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Fecha todos os outros submenus
        $('.dropdown-submenu .dropdown-menu').not($(this).next('.dropdown-menu')).slideUp().removeClass('show');

        // Alterna a exibição do submenu atual e aplica a classe 'show'
        $(this).next('.dropdown-menu').slideToggle().toggleClass('show');
    });

    // Fecha o submenu quando o dropdown principal é fechado
    $('.dropdown').on("hide.bs.dropdown", function() {
        $('.dropdown-menu .dropdown-menu').slideUp().removeClass('show');
    });
});

</script>

