@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Minha Conta</h1>

    <!-- Mensagem de sucesso -->
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
    </div>
</div>

<!-- Axios Script -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Função para atualizar os dados pessoais do usuário usando Axios
    function updateProfile() {
        // Obtém os dados do formulário
        const formData = new FormData(document.getElementById('updateProfileForm'));

        axios.post("{{ route('account.updateProfile') }}", formData)
            .then(response => {
                // Exibe mensagem de sucesso
                document.getElementById('successMessage').innerHTML = response.data.message;
                document.getElementById('successMessage').classList.remove('d-none');
                document.getElementById('errorMessage').classList.add('d-none');
            })
            .catch(error => {
                // Exibe mensagem de erro, caso existam erros de validação
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
</script>
@endsection
