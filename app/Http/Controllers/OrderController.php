<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
    
        // Limpa o carrinho e o desconto após o pedido ser confirmado
        session()->forget(['cart', 'discount']);
    
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
    ]);

    // Salvar a avaliação
    $order->rating = $request->input('rating');
    $order->save();

    return redirect()->back()->with('success', 'Avaliação enviada com sucesso!');
}
public function updateStatus(Request $request, Order $order)
{
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

    return redirect()->back()->with('success', 'Status do pedido atualizado com sucesso!');
}



}
