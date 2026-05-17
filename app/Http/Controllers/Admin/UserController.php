<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Verificar se é administrador
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // 2. Pegar a empresa selecionada
        if (session()->has('selected_company_id')) {
            $companyId = session('selected_company_id');
        } else {
            $companyId = Auth::user()->company_id ?? 1;
        }
        
        // 3. IMPORTANTE: Buscar usuários INCLUINDO os desativados (withTrashed)
        $query = User::where('company_id', $companyId)
                     ->with(['company', 'sector'])
                     ->withTrashed(); // <-- LINHA MÁGICA! Mostra usuários inativos
        
        // 4. Filtrar por nome ou email (se o campo não estiver vazio)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // 5. Filtrar por perfil (admin, tecnico, usuario)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // 6. Filtrar por status (ativo ou inativo)
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->whereNull('deleted_at');  // Apenas ativos
            } elseif ($request->status == 'inactive') {
                $query->whereNotNull('deleted_at'); // Apenas inativos
            }
        }
        
        // 7. Paginar os resultados (15 por página)
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $users->appends($request->all());
        
        // 8. Mostrar a tela com os usuários
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $sectors = Sector::orderBy('name')->get();
        
        return view('admin.users.create', compact('companies', 'sectors'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'company_id' => 'required|exists:companies,id',
            'sector_id' => 'required|exists:sectors,id',
            'role' => 'required|in:admin,technician,user',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $request->company_id,
            'sector_id' => $request->sector_id,
            'email_verified_at' => now(),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', "Usuário {$user->name} criado com sucesso!");
    }

    public function edit(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $sectors = Sector::where('company_id', $user->company_id)->orderBy('name')->get();
        
        return view('admin.users.edit', compact('user', 'companies', 'sectors'));
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company_id' => 'required|exists:companies,id',
            'sector_id' => 'required|exists:sectors,id',
            'role' => 'required|in:admin,technician,user',
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->company_id = $request->company_id;
        $user->sector_id = $request->sector_id;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', "Usuário {$user->name} atualizado com sucesso!");
    }

    public function destroy(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode excluir seu próprio usuário.');
        }
        
        $user->forceDelete();
        
        return redirect()->route('admin.users.index')
            ->with('success', "Usuário {$user->name} excluído permanentemente com sucesso!");
    }
    
    public function activate($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // Buscar incluindo os deletados (com Trashed)
        $user = User::withTrashed()->findOrFail($id);
        $user->restore(); // Restaurar da lixeira
        
        return redirect()->route('admin.users.index')
            ->with('success', "Usuário {$user->name} reativado com sucesso!");
    }
    
    public function deactivate($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode desativar seu próprio usuário.');
        }
        
        // Mandar para a lixeira (soft delete)
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', "Usuário {$user->name} desativado com sucesso!");
    }
}