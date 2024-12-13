<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Cria um novo pedido a partir dos itens e do total fornecido no request
    public function createOrder(Request $request)
    {
        // Exibe os dados para depuração (remova isso para produção)
        // dd($request); 

        // Validação dos itens e do total
        $request->validate([
            'items' => 'required|array',
            'total' => 'required|numeric',
        ]);

        // Cria um novo pedido associado ao usuário logado
        $order = Order::create([
            'user_id' => $request->user()->id,
            'status' => 'Pendente',
            'total' => $request->total,
        ]);

        // Adiciona cada item ao pedido criado
        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_name' => $item['name'],  // Garante que o campo `item_name` está sendo passado corretamente
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json($order, 201);
    }

    // Função para teste de formulário (pode ser removida posteriormente)
    public function testeFormulario(Request $request)
    {
        // Criação de um pedido simples (não recomendado em produção sem validação)
        Order::create($request->all());
        dd($request);
    }

    // Obtém o histórico de pedidos do usuário logado
    public function getOrderHistory(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->with('items')->get();
        return response()->json($orders);
    }

    // Função de teste para verificar o funcionamento da rota
    public function index()
    {
        dd('Rota funcionando corretamente!');
    }

    // Cria um pedido a partir do carrinho e redireciona para o resumo
    public function store(Request $request)
    {
        $user = Auth::user();

        // Verifica se o endereço do usuário está preenchido
        if (empty($user->address)) {
            return response()->json(['message' => 'Por favor, preencha o endereço de entrega antes de confirmar o pedido.'], 400);
        }

        // Verifica se o número do usuário está preenchido
        if (empty($user->phone)) {
            return response()->json(['message' => 'Por favor, preencha o número de telefone antes de confirmar o pedido.'], 400);
        }

        $total = $request->input('total');
        
        // Calcula o total se ele não foi enviado
        if (!$total) {
            $total = 0;
            foreach (session('cart') as $id => $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
    
        // Aplica o desconto, se houver
        $discount = session('discount', 0); // Obtém o desconto da sessão (0 se não houver desconto)
        $total = $total - ($total * $discount / 100);
    
        // Verifica se há estoque disponível para cada produto
        foreach (session('cart') as $id => $item) {
            $product = Product::find($id);
    
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => 'Estoque insuficiente para o produto ' . $product->name
                ], 400);
            }
        }
    
        // Gera um número de pedido único
        $orderNumber = strtoupper(uniqid('PED'));
    
        // Cria o pedido no banco de dados
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => $orderNumber,
            'status' => 'Pendente',
            'payment_method' => $request->input('paymentMethod'),
            'delivery_address' => Auth::user()->address,
            'total' => $total,
        ]);
    
        // Adiciona os itens ao pedido e reduz o estoque
        foreach (session('cart') as $id => $item) {
            $product = Product::find($id);
    
            // Reduz o estoque do produto
            $product->stock -= $item['quantity'];
            $product->save();
    
            // Cria os itens do pedido
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'item_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
    
            // Verifica se o produto tem atributos (ingredientes) selecionados e salva
            if (!empty($item['attributes'])) {
                foreach ($item['attributes'] as $attribute) {
                    $orderItem->attributes()->attach($attribute['id']);
                }
            }
        }
    
        // Calcula os pontos de fidelidade com base no total final do pedido (1 ponto a cada 10 reais)
        $points = floor($total / 10);
    
        // Armazena os pontos na tabela de pontos de fidelidade
        LoyaltyPoint::create([
            'user_id' => Auth::id(),
            'points' => $points,
            'order_id' => $order->id,
        ]);
    
        // Verifica se há um cupom aplicado e se ele é único, então o remove
        $couponCode = session('coupon_code'); // Certifique-se de que o código do cupom está salvo na sessão
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
    
            if ($coupon && $coupon->is_unique) {
                $coupon->delete(); // Remove o cupom se for único
                \Log::info("Cupom {$couponCode} deletado com sucesso."); // Confirmação no log
            } else {
                \Log::warning("Cupom {$couponCode} não foi deletado. Código não encontrado ou não é único.");
            }
        } else {
            \Log::warning("Nenhum código de cupom encontrado na sessão.");
        }
    
        // Limpa o carrinho, o desconto e o cupom após o pedido ser confirmado
        session()->forget(['cart', 'discount', 'coupon_code']);
    
        // Retorna a resposta JSON com a URL de redirecionamento
        return response()->json([
            'message' => 'Pedido realizado com sucesso!',
            'redirect_url' => route('orders.summary', ['order' => $order->id])
        ]);
    }
    
    
    
    
    
    
    // Exibe a tela de resumo de um pedido específico
    public function showSummary($id)
    {
        // Obtém o pedido com os itens associados usando Eloquent
        $order = Order::with('items')->findOrFail($id);

        // Exibe a view `order_summary` passando os dados do pedido
        return view('order_summary', compact('order'));
    }

    public function userOrders(Request $request)
    {
        // Obtém o ID do usuário autenticado
        $userId = Auth::id();
    
        // Pega o valor de ordenação da requisição (ascendente ou descendente)
        $sortOrder = $request->input('sort', 'desc'); // Padrão será descendente (mais recentes primeiro)
    
        // Busca todos os pedidos do usuário, incluindo os itens relacionados e ordena pela data de acordo com a seleção do usuário
        $orders = Order::where('user_id', $userId)
                       ->with('items')
                       ->orderBy('created_at', $sortOrder)  // Ordena de acordo com o valor do sortOrder
                       ->get();
    
        // Retorna a view com os pedidos do usuário e a escolha de ordenação
        return view('orders.user_orders', compact('orders', 'sortOrder'));
    }
    
    public function rate(Request $request, Order $order)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_comment' => 'nullable|string|max:500', // Validação para o campo de comentário
        ]);
    
        // Salvar a avaliação e o comentário
        $order->rating = $request->input('rating');
        $order->review_comment = $request->input('review_comment'); // Salva o comentário se presente
        $order->save();
    
        return redirect()->back()->with('success', 'Avaliação enviada com sucesso!');
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        // Validação do status enviado
        $request->validate([
            'status' => 'required|in:Pendente,Em preparo,Saiu para entrega,Entregue',
        ]);
    
        // Atualiza o status do pedido
        $order->status = $request->input('status');
        $order->save();
    
        // Notificar o usuário sobre a atualização do status
        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusUpdated($order));
        }
    
        // Retorna resposta JSON se a requisição for AJAX
        if ($request->ajax()) {
            return response()->json(['message' => 'Status do pedido atualizado com sucesso!']);
        }
    
        // Redireciona de volta com uma mensagem de sucesso caso não seja uma requisição AJAX
        return redirect()->back()->with('success', 'Status do pedido atualizado com sucesso!');
    }
    
public function reviewReport(Request $request)
{
    // Filtrar por cliente e estrelas, se fornecidos
    $query = Order::query()->whereNotNull('rating');

    if ($request->filled('customer_id')) {
        $query->where('user_id', $request->customer_id);
    }

    if ($request->filled('stars')) {
        $query->where('rating', $request->stars);
    }

    // Aplicar a ordenação por data
    $sortOrder = $request->input('sort_order', 'desc'); // Padrão para mais recente
    $query->orderBy('created_at', $sortOrder);

    // Obter as avaliações com informações do cliente
    $reviews = $query->with('user')->get();

    // Obter todos os clientes para o filtro
    $customers = User::all();

    return view('reviews.report', compact('reviews', 'customers', 'sortOrder'));
}




}
