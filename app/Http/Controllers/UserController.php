<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Método de login para autenticação e redirecionamento
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home')->with('success', 'Login efetuado com sucesso!');
        }

        return redirect()->route('login')->with('error', 'Credenciais inválidas!');
    }

    // Método para registrar um novo usuário
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Usuário criado com sucesso! Faça login.');
    }

    // Método para deslogar o usuário
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Você foi deslogado com sucesso!');
    }

    // Atualização de endereço e telefone (exclusiva)
    public function updateAddressPhone(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->save();

    // Redirecionar para a página atual (com a modal aberta) e exibir a mensagem de sucesso
        return redirect()->back()->with('success', 'Dados de endereço e telefone atualizados com sucesso!');
    }

    // Atualização completa do perfil do usuário
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Dados atualizados com sucesso!'], 200);

    }

    public function updatePassword(Request $request)
    {
        // Obtém o usuário autenticado
        $user = Auth::user();
    
        // Valida os campos recebidos
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'O campo senha atual é obrigatório.',
            'password.required' => 'O campo nova senha é obrigatório.',
            'password.min' => 'A nova senha deve ter no mínimo 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde à nova senha.',
        ]);
    
        // Verifica se a senha atual está correta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }
    
        // Atualiza a senha no banco de dados
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Redireciona com mensagem de sucesso
        return redirect()->route('account.edit')->with('success', 'Senha alterada com sucesso!');
    }
    
    
    // Método para exibir a página de edição de perfil
    public function edit()
    {
        $user = Auth::user();
        return view('account', compact('user'));
    }

}
