<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySwitchController extends Controller
{
    public function switch(Request $request, $companyId = null)
    {
        $user = Auth::user();
        
        // Permitir apenas Admin e Técnico
        if (!$user->isAdmin() && !$user->isTechnician()) {
            abort(403, 'Acesso negado. Apenas administradores e técnicos podem trocar de empresa.');
        }
        
        // Se veio de POST (formulário de seleção)
        if ($request->isMethod('post')) {
            $companyId = $request->company_id;
        }
        
        $company = Company::findOrFail($companyId);
        
        session(['selected_company_id' => $company->id]);
        session(['selected_company_name' => $company->name]);
        
        return redirect()->route('dashboard')
            ->with('success', "Empresa alterada para: {$company->name}");
    }
    
    public function reset()
    {
        $user = Auth::user();
        
        // Usuário comum: volta para empresa do seu cadastro
        session(['selected_company_id' => $user->company_id]);
        session(['selected_company_name' => $user->company->name ?? '']);
        
        return redirect()->route('dashboard')
            ->with('success', 'Voltou para sua empresa padrão.');
    }
}