<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Verifica se a requisição espera uma resposta JSON (como em AJAX)
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Acesso negado. Você não tem permissão para acessar esta área.'], 403);
        }

        // Para requisições normais, redireciona para a página inicial com mensagem de erro
        return redirect('/')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
