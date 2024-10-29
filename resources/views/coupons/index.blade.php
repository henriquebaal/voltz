@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cupons Ativos</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Código</th>
                <th>Desconto (%)</th>
                <th>Válido a partir de</th>
                <th>Válido até</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->discount }}%</td>
                    <td>{{ $coupon->valid_from ? $coupon->valid_from->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $coupon->valid_until->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este cupom?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum cupom ativo encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Botão centralizado para cadastrar novo cupom -->
    <div class="d-flex justify-content-center mt-4">
        <a href="{{ route('coupons.create') }}" class="btn btn-primary">Cadastrar Novo Cupom</a>
    </div>
</div>
@endsection
