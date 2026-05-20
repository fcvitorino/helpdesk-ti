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
            abort(403);
        }
        
        $sectors = Sector::with('company')->orderBy('name')->paginate(15);
        
        return view('admin.sectors.index', compact('sectors'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.sectors.create', compact('companies'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);
        
        Sector::create($request->all());
        
        return redirect()->route('admin.sectors.index')
            ->with('success', 'Setor criado com sucesso!');
    }

    public function edit(Sector $sector)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.sectors.edit', compact('sector', 'companies'));
    }

    public function update(Request $request, Sector $sector)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);
        
        $sector->update($request->all());
        
        return redirect()->route('admin.sectors.index')
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function destroy(Sector $sector)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $sector->delete();
        
        return redirect()->route('admin.sectors.index')
            ->with('success', 'Setor excluído com sucesso!');
    }
    
    /**
     * Buscar setores por empresa (para AJAX)
     */
    public function getByCompany($companyId)
    {
        $sectors = Sector::where('company_id', $companyId)
                         ->orderBy('name')
                         ->get(['id', 'name']);
        
        return response()->json($sectors);
    }
}