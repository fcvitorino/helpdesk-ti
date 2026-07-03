<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setor;
use App\Helpers\EmpresaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $empresaId = EmpresaHelper::getId();
        $users = User::where('empresa_id', $empresaId)->paginate(10);
        return view('usuarios.index', compact('users'));
    }

    public function create()
    {
        $setores = Setor::daEmpresaAtual()->pluck('nome', 'id');
        return view('usuarios.create', compact('setores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'tipo' => 'required|in:admin,tecnico,usuario',
            'setor_id' => 'nullable|exists:setores,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo' => $request->tipo,
            'setor_id' => $request->setor_id,
            'empresa_id' => EmpresaHelper::getId(),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado!');
    }

    public function edit(User $user)
    {
        if ($user->empresa_id != EmpresaHelper::getId()) {
            abort(403);
        }
        $setores = Setor::daEmpresaAtual()->pluck('nome', 'id');
        return view('usuarios.edit', compact('user', 'setores'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->empresa_id != EmpresaHelper::getId()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'tipo' => 'required|in:admin,tecnico,usuario',
            'setor_id' => 'nullable|exists:setores,id'
        ]);

        $user->update($request->only('name', 'email', 'tipo', 'setor_id'));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $user)
    {
        if ($user->empresa_id != EmpresaHelper::getId()) {
            abort(403);
        }
        $user->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuário excluído!');
    }
}