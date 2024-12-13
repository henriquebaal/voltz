<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Coupon;

class CartController extends Controller
{

    
    public function show()
    {
        // Obter o carrinho da sessão
        $cart = session()->get('cart', []);
    
        // Calcular o total do pedido
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    
        // Retornar a view com os itens do carrinho e o total calculado
        return view('cart', compact('cart', 'total'));
    }
    

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $attributes = $request->input('attributes', []); // IDs dos ingredientes, ou array vazio se não houver
        
        // Lógica para adicionar o produto ao carrinho
        $product = Product::find($productId);
        $cart = session()->get('cart', []);
        // Obter os atributos selecionados pelo usuário
        $selectedAttributes = Attribute::whereIn('id', $attributes)->get();

        
        // Criação do item no carrinho
        $cartItem = [
            'name' => $product->name,
            'quantity' => $quantity,
            'price' => $product->price,
            'attributes' => !empty($attributes) ? Attribute::whereIn('id', $attributes)->get() : [],
        ];
    
        // Adicionar ou atualizar o item no carrinho
        $cart[$productId] = $cartItem;
        session()->put('cart', $cart);
    
        // Calcular a quantidade total de itens no carrinho
        $cartCount = array_sum(array_column($cart, 'quantity')); // Soma as quantidades de todos os itens
    
        // Retornar a resposta com mensagem e o número de itens no carrinho
        return response()->json(['message' => 'Produto adicionado ao carrinho!', 'cartCount' => $cartCount]);
    }
    

    

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.show')->with('success', 'Quantidade atualizada com sucesso!');
        }

        return redirect()->route('cart.show')->with('error', 'Produto não encontrado no carrinho!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('cart.show')->with('success', 'Produto removido do carrinho com sucesso!');
        }

        return redirect()->route('cart.show')->with('error', 'Produto não encontrado no carrinho!');
    }

        // Aplica um cupom de desconto no carrinho
        public function applyCoupon(Request $request)
        {
            $request->validate([
                'coupon_code' => 'required|string'
            ]);
    
            $coupon = Coupon::where('code', $request->coupon_code)
                            ->where('valid_until', '>=', now())
                            ->first();
    
            if (!$coupon) {
                return response()->json(['error' => 'Cupom inválido ou expirado.'], 400);
            }
    
            // Salva o valor do desconto e o código do cupom na sessão
            session(['discount' => $coupon->discount, 'coupon_code' => $coupon->code]);

    
            // Calcula o total com desconto
            $cart = session('cart', []);
            $total = array_reduce($cart, function ($sum, $item) {
                return $sum + $item['price'] * $item['quantity'];
            }, 0);
    
            $discountedTotal = $total - ($total * $coupon->discount / 100);
    
            return response()->json([
                'success' => 'Cupom aplicado com sucesso!',
                'discount' => $coupon->discount,
                'discountedTotal' => $discountedTotal
            ]);
        }
        

}
