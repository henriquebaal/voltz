<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->is_admin) {
            return $next($request);
        }
        
        return redirect('/')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
