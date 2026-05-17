<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sector;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SectorController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        // Se for admin e tiver uma empresa selecionada, usa ela. Senão, usa a empresa do usuário
        if (session()->has('selected_company_id')) {
            $companyId = session('selected_company_id');
        } else {
            $companyId = Auth::user()->company_id;
        }
        
        $sectors = Sector::where('company_id', $companyId)->orderBy('name')->paginate(15);
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.sectors.index', compact('sectors', 'companies', 'companyId'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.sectors.create', compact('companies'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'company_id' => 'required|exists:companies,id'
        ]);

        Sector::create($validated);

        return redirect()->route('admin.sectors.index')
            ->with('success', 'Setor criado com sucesso!');
    }

    public function show(Sector $sector)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        return view('admin.sectors.show', compact('sector'));
    }

    public function edit(Sector $sector)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.sectors.edit', compact('sector', 'companies'));
    }

    public function update(Request $request, Sector $sector)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'company_id' => 'required|exists:companies,id'
        ]);

        $sector->update($validated);

        return redirect()->route('admin.sectors.index')
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function destroy(Sector $sector)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        // Verificar se existem chamados vinculados a este setor
        if ($sector->tickets()->count() > 0) {
            return back()->with('error', 'Não é possível excluir um setor que possui chamados vinculados.');
        }
        
        // Verificar se existem usuários vinculados a este setor
        if ($sector->users()->count() > 0) {
            return back()->with('error', 'Não é possível excluir um setor que possui usuários vinculados.');
        }
        
        $sector->delete();
        
        return redirect()->route('admin.sectors.index')
            ->with('success', 'Setor excluído com sucesso!');
    }
}