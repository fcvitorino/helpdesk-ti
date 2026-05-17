<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySwitchController extends Controller
{
    public function switch($companyId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        $company = Company::findOrFail($companyId);
        
        session(['selected_company_id' => $company->id]);
        session(['selected_company_name' => $company->name]);
        
        return redirect()->back()->with('success', 'Visualizando empresa: ' . $company->name);
    }

    public function reset()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        session()->forget('selected_company_id');
        session()->forget('selected_company_name');
        
        return redirect()->back()->with('success', 'Voltou para sua empresa padrão.');
    }
}