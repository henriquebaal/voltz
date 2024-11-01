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

                    <!-- Link "Dashboard" visível apenas para administradores -->
                    @if(Auth::user() && Auth::user()->is_admin)
                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    @endif

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
                        <a class="dropdown-item" href="{{ route('report.sales') }}">Vendas</a>
                        <!-- Link "Cadastro de Cupons" visível apenas para administradores -->
                        <a class="dropdown-item" href="{{ route('coupons.index') }}">Marketing</a>
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
