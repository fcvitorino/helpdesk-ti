<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\User;
use App\Models\Setor;
use App\Helpers\EmpresaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChamadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $empresaId = EmpresaHelper::getId();
        if (Auth::user()->tipo === 'admin') {
            $chamados = Chamado::where('empresa_id', $empresaId)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $chamados = Chamado::where('user_id', Auth::id())
                ->where('empresa_id', $empresaId)
                ->orWhere('responsavel_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return view('chamados.index', compact('chamados'));
    }

    public function create()
    {
        // FILTRO: setores e usuários apenas da empresa atual
        $setores = Setor::daEmpresaAtual()->pluck('nome', 'id');
        $responsaveis = User::where('empresa_id', EmpresaHelper::getId())->get();
        return view('chamados.create', compact('setores', 'responsaveis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta',
            'responsavel_id' => 'nullable|exists:users,id',
            'setor_id' => 'nullable|exists:setores,id'
        ]);

        $chamado = Chamado::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'prioridade' => $request->prioridade,
            'status' => 'aberto',
            'user_id' => Auth::id(),
            'responsavel_id' => $request->responsavel_id,
            'setor_id' => $request->setor_id,
            'empresa_id' => EmpresaHelper::getId(),
        ]);

        // Disparar notificação (se tiver)
        // ...

        return redirect()->route('chamados.show', $chamado->id)
            ->with('success', 'Chamado criado com sucesso!');
    }

    public function show($id)
    {
        $chamado = Chamado::with('user', 'responsavel', 'setor')->findOrFail($id);
        if (Auth::user()->tipo !== 'admin' && Auth::id() !== $chamado->user_id && Auth::id() !== $chamado->responsavel_id) {
            abort(403);
        }
        return view('chamados.show', compact('chamado'));
    }

    public function edit($id)
    {
        $chamado = Chamado::findOrFail($id);
        if (Auth::user()->tipo !== 'admin' && Auth::id() !== $chamado->responsavel_id) {
            abort(403);
        }
        // FILTRO: setores e usuários apenas da empresa atual
        $setores = Setor::daEmpresaAtual()->pluck('nome', 'id');
        $responsaveis = User::where('empresa_id', EmpresaHelper::getId())->get();
        return view('chamados.edit', compact('chamado', 'setores', 'responsaveis'));
    }

    public function update(Request $request, $id)
    {
        $chamado = Chamado::findOrFail($id);
        if (Auth::user()->tipo !== 'admin' && Auth::id() !== $chamado->responsavel_id) {
            abort(403);
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta',
            'status' => 'required|in:aberto,andamento,fechado',
            'responsavel_id' => 'nullable|exists:users,id',
            'setor_id' => 'nullable|exists:setores,id'
        ]);

        $chamado->update($request->all());

        return redirect()->route('chamados.show', $chamado->id)
            ->with('success', 'Chamado atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $chamado = Chamado::findOrFail($id);
        if (Auth::user()->tipo !== 'admin') {
            abort(403);
        }
        $chamado->delete();
        return redirect()->route('chamados.index')
            ->with('success', 'Chamado excluído!');
    }

    public function minhasNotificacoes()
    {
        $notificacoes = Auth::user()->notifications()->paginate(10);
        return view('chamados.notificacoes', compact('notificacoes'));
    }

    public function marcarNotificacaoLida($id)
    {
        $notificacao = Auth::user()->notifications()->findOrFail($id);
        $notificacao->markAsRead();
        return back()->with('success', 'Notificação marcada como lida!');
    }

    public function marcarTodasNotificacoesLidas()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas notificações marcadas como lidas!');
    }
}