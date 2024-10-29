<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Hamburgueria</a>
    <!-- Botão de colapso no mobile (hamburger menu) -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Pedidos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Minha Conta</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Logoff</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stock.index') }}">Estoque</a>
            </li>
        </ul>
    </div>
</nav>
