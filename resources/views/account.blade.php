@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Minha Conta</h1>

    <!-- Mensagem de sucesso e erro -->
    <div id="successMessage" class="alert alert-success d-none"></div>
    <div id="errorMessage" class="alert alert-danger d-none"></div>

    <div class="row">
        <!-- Seção de Atualização dos Dados Pessoais -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Atualizar Dados Pessoais</h5>
                </div>
                <div class="card-body">
                    <!-- Formulário de atualização de dados usando Axios -->
                    <form id="updateProfileForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nome Completo</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="address">Endereço</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <!-- Botão de atualização -->
                        <button type="button" class="btn btn-primary" onclick="updateProfile()">Atualizar Dados</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Seção de Alteração de Senha -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Redefinir Senha</h5>
                </div>
                <div class="card-body">
                    <!-- Exibir Mensagens de Erro de Validação -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulário de atualização de senha -->
                    <form method="POST" action="{{ route('account.updatePassword') }}">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">Senha Atual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Nova Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Exibir pontos de fidelidade na seção "Minha Conta" -->
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Programa de Fidelidade</h5>
                </div>
                <div class="card-body">
                    <p>Você tem <strong>{{ $user->totalLoyaltyPoints() }}</strong> pontos.</p>
                    
                    <!-- Exibir botão se o usuário tiver 100 pontos ou mais -->
                    @if ($user->totalLoyaltyPoints() >= 100)
                        <button id="redeemCouponButton" class="btn btn-success" onclick="redeemCoupon()">Resgatar Cupom de 50% de Desconto</button>
                    @else
                        <p>Acumule 100 pontos para resgatar um cupom de 50% de desconto.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para exibir o cupom resgatado -->
<div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">Cupom Resgatado!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Código do Cupom:</strong> <span id="couponCode"></span></p>
                <p>Instrução: Use o código acima ao finalizar a compra para obter um desconto de 50%!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Axios Script -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function updateProfile() {
        const formData = new FormData(document.getElementById('updateProfileForm'));

        axios.post("{{ route('account.updateProfile') }}", formData)
            .then(response => {
                document.getElementById('successMessage').innerHTML = response.data.message;
                document.getElementById('successMessage').classList.remove('d-none');
                document.getElementById('errorMessage').classList.add('d-none');
            })
            .catch(error => {
                let errorMessage = '';
                if (error.response && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += `<p>${errors[key][0]}</p>`;
                        }
                    }
                } else {
                    errorMessage = 'Ocorreu um erro ao atualizar os dados.';
                }

                document.getElementById('errorMessage').innerHTML = errorMessage;
                document.getElementById('errorMessage').classList.remove('d-none');
                document.getElementById('successMessage').classList.add('d-none');
            });
    }

    function redeemCoupon() {
        axios.post("{{ route('account.redeemLoyaltyCoupon') }}", {
            _token: '{{ csrf_token() }}'
        })
        .then(response => {
            document.getElementById('couponCode').textContent = response.data.coupon_code;
            $('#couponModal').modal('show'); // Exibir a modal com o cupom
        })
        .catch(error => {
            const errorMessage = error.response && error.response.data.error
                ? error.response.data.error
                : 'Ocorreu um erro ao resgatar o cupom.';
            document.getElementById('errorMessage').innerHTML = errorMessage;
            document.getElementById('errorMessage').classList.remove('d-none');
            document.getElementById('successMessage').classList.add('d-none');
        });
    }
</script>
@endsection
