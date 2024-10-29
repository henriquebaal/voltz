<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{
    // Exibir todos os produtos cadastrados no estoque
    public function index()
    {
        $products = Product::all();
        return view('stock', compact('products'));
    }

    // Exibir a view para criar um novo produto
    public function create()
    {
        return view('products.create');
    }

    // Armazenar um novo produto no estoque
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Armazenar a imagem, se houver
        $fileName = null;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = \Str::random(40) . '.' . $extension;

            // Armazenar a imagem no diretório público
            $fileName = $request->file('image')->storeAs('products', $fileName, 'public');
        }

        // Criar o produto no banco de dados com os dados validados
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $fileName,
        ]);

        // Redirecionar para a view de estoque com uma mensagem de sucesso
        return redirect()->route('stock.index')->with('success', 'Produto incluído com sucesso no estoque!');
    }

    // Remover um produto do estoque
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Remover a imagem associada, se existir
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Remover o produto do banco de dados
        $product->delete();

        // Redirecionar para a página de estoque com uma mensagem de sucesso
        return redirect()->route('stock.index')->with('success', 'Produto removido com sucesso!');
    }

    public function addStock(Request $request, Product $product)
{
    $quantity = $request->input('quantity');
    if($quantity <= 0) {
        return redirect()->back()->with('error', 'A quantidade deve ser maior que zero.');
    }

    $product->stock += $quantity;
    $product->save();

    return redirect()->back()->with('success', 'Estoque atualizado com sucesso!');
}

public function removeStock(Request $request, Product $product)
{
    $quantity = $request->input('quantity');
    if($quantity <= 0) {
        return redirect()->back()->with('error', 'A quantidade deve ser maior que zero.');
    }

    $product->stock -= $quantity;
    $product->save();

    return redirect()->back()->with('success', 'Estoque atualizado com sucesso!');
}

}
