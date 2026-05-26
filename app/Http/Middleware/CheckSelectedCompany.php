<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSelectedCompany
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // APENAS Admin e Técnico precisam selecionar empresa
        // Usuário comum NÃO precisa
        if ($user && ($user->isAdmin() || $user->isTechnician())) {
            if (!session()->has('selected_company_id')) {
                return redirect()->route('company.select')
                    ->with('warning', 'Selecione uma empresa para acessar o sistema.');
            }
        }
        
        // Usuário comum: garantir que ele só veja seus próprios dados
        if ($user && !$user->isAdmin() && !$user->isTechnician()) {
            // Forçar empresa do próprio cadastro
            session(['selected_company_id' => $user->company_id]);
            session(['selected_company_name' => $user->company->name ?? '']);
        }
        
        return $next($request);
    }
}