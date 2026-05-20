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
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $companies = Company::orderBy('name')->paginate(15);
        
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'cnpj' => 'nullable|string|max:18|unique:companies,cnpj',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        
        Company::create([
            'name' => $request->name,
            'cnpj' => $request->cnpj,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.companies.index')
            ->with('success', 'Empresa criada com sucesso!');
    }

    public function show(Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'cnpj' => 'nullable|string|max:18|unique:companies,cnpj,' . $company->id,
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        
        $company->update([
            'name' => $request->name,
            'cnpj' => $request->cnpj,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.companies.index')
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        if ($company->users()->count() > 0) {
            return redirect()->route('admin.companies.index')
                ->with('error', 'Não é possível excluir uma empresa que possui usuários vinculados.');
        }
        
        $company->delete();
        
        return redirect()->route('admin.companies.index')
            ->with('success', 'Empresa excluída com sucesso!');
    }
    
    public function toggleActive(Company $company)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $company->is_active = !$company->is_active;
        $company->save();
        
        return redirect()->route('admin.companies.index')
            ->with('success', 'Status da empresa alterado com sucesso!');
    }
}