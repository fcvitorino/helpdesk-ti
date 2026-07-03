<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use App\Helpers\EmpresaHelper;
use Illuminate\Http\Request;

class SetorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista setores da empresa atual
     */
    public function index()
    {
        $setores = Setor::daEmpresaAtual()->orderBy('nome')->paginate(10);
        return view('setores.index', compact('setores'));
    }

    public function create()
    {
        return view('setores.create');
    }

    /**
     * Cria um novo setor vinculado à empresa atual
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $empresaId = EmpresaHelper::getId();

        Setor::create([
            'nome' => $request->nome,
            'empresa_id' => $empresaId,
        ]);

        return redirect()->route('setores.index')
            ->with('success', 'Setor criado com sucesso!');
    }

    public function edit(Setor $setor)
    {
        // Verifica se o setor pertence à empresa atual
        if ($setor->empresa_id != EmpresaHelper::getId()) {
            abort(403, 'Setor não pertence à sua empresa.');
        }
        return view('setores.edit', compact('setor'));
    }

    public function update(Request $request, Setor $setor)
    {
        if ($setor->empresa_id != EmpresaHelper::getId()) {
            abort(403, 'Setor não pertence à sua empresa.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $setor->update(['nome' => $request->nome]);

        return redirect()->route('setores.index')
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function destroy(Setor $setor)
    {
        if ($setor->empresa_id != EmpresaHelper::getId()) {
            abort(403, 'Setor não pertence à sua empresa.');
        }
        $setor->delete();
        return redirect()->route('setores.index')
            ->with('success', 'Setor excluído com sucesso!');
    }
}