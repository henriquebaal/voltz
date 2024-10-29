<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Pagination\Paginator;

class AdminController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count(); // Contagem total de pedidos
        $totalUsers = User::count(); // Contagem total de usuários
    
        // Apenas pedidos recentes com paginação (10 pedidos por página)
        $recentOrders = Order::orderBy('created_at', 'desc')->paginate(10);
    
        return view('admin.dashboard', compact('totalOrders', 'totalUsers', 'recentOrders'));
    }

    
}
