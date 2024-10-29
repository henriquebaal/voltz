<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    public function showReport(Request $request)
    {
        // Definindo o tipo de relatório, com o padrão sendo 'daily'
        $reportType = $request->input('type', 'daily');
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m')); // Mês selecionado, padrão mês atual

        // Obter dados do relatório com base no tipo
        $reportData = $this->getReportData($reportType, $selectedMonth);

        // Retornar a view com os dados do relatório, tipo de relatório selecionado e o mês escolhido
        return view('reports.sales', [
            'reportData' => $reportData,
            'reportType' => $reportType,
            'selectedMonth' => $selectedMonth,
        ]);
    }

    private function getReportData($reportType, $selectedMonth)
    {
        switch ($reportType) {
            case 'daily':
                // Agrupa as vendas por dia nos últimos 30 dias
                $reportData = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total_sales')
                )
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
                break;

            case 'weekly':
                // Define o primeiro e o último dia do mês selecionado
                $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
                $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

                // Agrupa as vendas por semana no mês selecionado
                $reportData = Order::select(
                    DB::raw('WEEK(created_at, 1) - WEEK(DATE_SUB(created_at, INTERVAL DAYOFMONTH(created_at)-1 DAY), 1) + 1 as week_of_month'),
                    DB::raw('SUM(total) as total_sales')
                )
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->groupBy('week_of_month')
                ->orderBy('week_of_month', 'asc')
                ->get();
                break;

            case 'monthly':
                // Agrupa as vendas por mês durante o último ano
                $reportData = Order::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total) as total_sales')
                )
                ->where('created_at', '>=', Carbon::now()->subYear())
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
                break;

            case 'total_sales':
                // Soma total geral de vendas, sem agrupamento
                $reportData = Order::sum('total');
                break;;

            case 'top_items':
                // Relatório de Itens Mais Vendidos
                $reportData = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->select('order_items.item_name', DB::raw('SUM(order_items.quantity) as total_quantity'))
                    ->groupBy('order_items.item_name')
                    ->orderBy('total_quantity', 'desc')
                    ->get();
                break;

            default:
                $reportData = collect();
        }

        return $reportData;
    }
}
