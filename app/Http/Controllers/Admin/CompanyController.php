<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:companies',
            'ramo' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Company::create($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Empresa criada com sucesso!');
    }

    public function show(Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:companies,cnpj,' . $company->id,
            'ramo' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        
        if ($company->users()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma empresa que possui usuários vinculados.');
        }
        
        $company->delete();
        
        return redirect()->route('admin.companies.index')
            ->with('success', 'Empresa excluída com sucesso!');
    }
}