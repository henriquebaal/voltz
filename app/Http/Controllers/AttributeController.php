<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;

class AttributeController extends Controller
{
    public function create()
    {
        return view('attributes.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Criação do novo atributo
        Attribute::create([
            'name' => $request->input('name'),
        ]);

        // Redirecionar com mensagem de sucesso
        return redirect()->route('attributes.create')->with('success', 'Atributo cadastrado com sucesso!');
    }
}
