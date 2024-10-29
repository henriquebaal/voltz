<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Attribute;

class ProductController extends Controller
{
    public function index()
    {
        // Pegar todos os produtos e atributos
        $products = Product::all();
        $attributes = Attribute::all(); // Pegando todos os ingredientes disponíveis
    
        return view('home', compact('products', 'attributes'));
    }
    
    public function create()
    {
        // Retornar a view de criação de produtos com todos os atributos disponíveis
        $attributes = Attribute::all();
        return view('products.create', compact('attributes'));
    }

    // Processa o cadastro do produto
    public function store(Request $input)
    {
        // Validação dos dados
        $input->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'array', // Validação dos atributos como array
            'attributes.*' => 'exists:attributes,id', // Garantir que os atributos existam na tabela
        ]);
    
        // Armazenar a imagem, se houver
        $fileName = null;
        if ($input->hasFile('image')) {
            $extension = $input->file('image')->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $extension;
    
            // Armazenar a imagem com o nome gerado
            $Pasta = 'public/products';
            Storage::putFileAs($Pasta, $input->file('image'), $fileName);
        }
    
        // Criar o produto no banco de dados com o nome correto da imagem
        $product = Product::create([
            'name' => $input->name,
            'description' => $input->description,
            'price' => $input->price,
            'image' => $fileName,  // Usar o nome da imagem gerado
        ]);
       
        // Salvar os atributos (ingredientes) selecionados
        if ($input->has('attributes')) {
            $product->attributes()->sync($input->attributes);  // Relacionar atributos ao produto
        }
    
        // Verificar se o produto foi criado com sucesso e redirecionar para a view de estoque
        if ($product) {
            return redirect()->route('stock.index')->with('success', 'Produto incluído com sucesso no estoque!');
        } else {
            return redirect()->route('stock.index')->with('error', 'Erro ao cadastrar o produto. Tente novamente.');
        }
    }
}
