<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\Sector;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        if (session()->has('selected_company_id')) {
            $companyId = session('selected_company_id');
        } else {
            $companyId = Auth::user()->company_id ?? 1;
        }
        
        $query = User::where('company_id', $companyId)
                     ->with(['company', 'sector'])
                     ->withTrashed();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }
        
        $users = $query->latest()->paginate(15);
        $users->appends($request->all());
        
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
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'min:8', new StrongPassword],
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
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company_id' => 'required|exists:companies,id',
            'sector_id' => 'required|exists:sectors,id',
            'role' => 'required|in:admin,technician,user',
        ];
        
        // Se a senha foi preenchida, validar com regra forte
        if ($request->filled('password')) {
            $rules['password'] = ['min:8', 'confirmed', new StrongPassword];
        }
        
        $request->validate($rules);
        
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

  public function destroy($id)
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }

    // Busca o usuário mesmo se estiver inativo (soft delete)
    $user = User::withTrashed()->find($id);

    if (!$user) {
        return redirect()->route('admin.users.index')
            ->with('error', 'Usuário não encontrado.');
    }

    if ($user->id === Auth::id()) {
        return redirect()->route('admin.users.index')
            ->with('error', 'Você não pode excluir seu próprio usuário.');
    }

    // Exclui permanentemente (force delete)
    $user->forceDelete();

    return redirect()->route('admin.users.index')
        ->with('success', "Usuário {$user->name} excluído permanentemente com sucesso!");
}
    
    public function activate($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
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
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', "Usuário {$user->name} desativado com sucesso!");
    }
}