@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5">Relatório de Vendas - {{ ucfirst(str_replace('_', ' ', $reportType)) }}</h1>

    <!-- Formulário para seleção do tipo de relatório -->
    <form method="GET" action="{{ route('report.sales') }}" class="mb-4">
        <div class="form-group">
            <label for="reportType">Selecione o tipo de relatório:</label>
            <select name="type" id="reportType" class="form-control" onchange="this.form.submit()">
                <option value="daily" {{ $reportType == 'daily' ? 'selected' : '' }}>Diário</option>
                <option value="weekly" {{ $reportType == 'weekly' ? 'selected' : '' }}>Semanal</option>
                <option value="monthly" {{ $reportType == 'monthly' ? 'selected' : '' }}>Mensal</option>
                <option value="total_sales" {{ $reportType == 'total_sales' ? 'selected' : '' }}>Total de Vendas</option>
                <option value="top_items" {{ $reportType == 'top_items' ? 'selected' : '' }}>Itens Mais Vendidos</option>
            </select>
        </div>
    </form>

    @if($reportType == 'total_sales')
        <!-- Exibição do Total Geral de Vendas -->
        <h4 class="text-center">Valor Total de Vendas: R$ {{ number_format($reportData, 2, ',', '.') }}</h4>
    @elseif($reportType == 'daily' || $reportType == 'weekly' || $reportType == 'monthly')
        <!-- Exibição de Tabela para Relatórios Diário, Semanal e Mensal -->
        <h4>Dados de Vendas - {{ ucfirst(str_replace('_', ' ', $reportType)) }}</h4>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    @if($reportType == 'daily')
                        <th>Data</th>
                    @elseif($reportType == 'weekly')
                        <th>Semana do Mês</th>
                    @elseif($reportType == 'monthly')
                        <th>Ano</th>
                        <th>Mês</th>
                    @endif
                    <th>Total Vendido (R$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $data)
                    <tr>
                        @if($reportType == 'daily')
                            <td>{{ $data->date }}</td>
                        @elseif($reportType == 'weekly')
                            <td>Semana {{ $data->week_of_month }}</td>
                        @elseif($reportType == 'monthly')
                            <td>{{ $data->year }}</td>
                            <td>{{ $data->month }}</td>
                        @endif
                        <td>R$ {{ number_format($data->total_sales, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Gráfico de Barras para Relatórios Diário, Semanal e Mensal -->
        <canvas id="salesChart" width="400" height="200"></canvas>
    @elseif($reportType == 'top_items')
        <!-- Exibição do relatório de Itens Mais Vendidos -->
        <h4>Itens Mais Vendidos</h4>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantidade Vendida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{ $data->item_name }}</td>
                        <td>{{ $data->total_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Gráfico de Pizza para Itens Mais Vendidos com tamanho reduzido -->
        <div style="max-width: 400px; margin: 0 auto;">
            <canvas id="topItemsChart"></canvas>
        </div>
    @endif
</div>

<!-- Scripts para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($reportType == 'daily' || $reportType == 'weekly' || $reportType == 'monthly')
        // Dados para o Gráfico de Barras dos Relatórios Diário, Semanal e Mensal
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($reportData->pluck($reportType == 'monthly' ? 'month' : ($reportType == 'weekly' ? 'week_of_month' : 'date'))->toArray()) !!},
                datasets: [{
                    label: 'Vendas (R$)',
                    data: {!! json_encode($reportData->pluck('total_sales')->toArray()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                }
            }
        });
    @elseif($reportType == 'top_items')
        // Dados para o Gráfico de Pizza de Itens Mais Vendidos
        const topItemsCtx = document.getElementById('topItemsChart').getContext('2d');
        new Chart(topItemsCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($reportData->pluck('item_name')->toArray()) !!},
                datasets: [{
                    label: 'Itens Mais Vendidos',
                    data: {!! json_encode($reportData->pluck('total_quantity')->toArray()) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    @endif
</script>
@endsection
