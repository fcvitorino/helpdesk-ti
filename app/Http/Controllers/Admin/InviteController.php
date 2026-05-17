<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invite;
use App\Models\Company;
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $query = Invite::with(['company', 'sector']);
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhereHas('company', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'pending') {
                $query->whereNull('accepted_at')->where('expires_at', '>', now());
            } elseif ($request->status == 'accepted') {
                $query->whereNotNull('accepted_at');
            } elseif ($request->status == 'expired') {
                $query->whereNull('accepted_at')->where('expires_at', '<', now());
            }
        }
        
        $invites = $query->latest()->paginate(20);
        $invites->appends($request->all());
        
        return view('admin.invites.index', compact('invites'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $sectors = Sector::orderBy('name')->get();
        return view('admin.invites.create', compact('companies', 'sectors'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'emails' => 'required|email',
            'company_id' => 'required|exists:companies,id',
            'sector_id' => 'required|exists:sectors,id',
            'role' => 'required|in:admin,technician,user',
        ]);
        
        $email = trim($request->emails);
        
        // Verificar se já existe usuário com este email
        if (\App\Models\User::where('email', $email)->exists()) {
            return redirect()->route('admin.invites.create')->with('error', 'Este email já está cadastrado.');
        }
        
        // Verificar se já existe convite pendente
        $pending = Invite::where('email', $email)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();
            
        if ($pending) {
            return redirect()->route('admin.invites.create')->with('error', 'Este email já possui um convite pendente.');
        }
        
        $token = Str::random(64);
        
        $invite = Invite::create([
            'email' => $email,
            'token' => $token,
            'name' => $request->name,
            'company_id' => $request->company_id,
            'sector_id' => $request->sector_id,
            'role' => $request->role,
            'expires_at' => now()->addDays(7),
        ]);
        
        try {
            Mail::send('emails.invite', ['invite' => $invite], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Convite para o HelpDesk TI');
            });
            
            return redirect()->route('admin.invites.index')->with('success', "Convite enviado com sucesso para {$email}!");
        } catch (\Exception $e) {
            return redirect()->route('admin.invites.create')->with('error', 'Erro ao enviar email: ' . $e->getMessage());
        }
    }

    public function destroy(Invite $invite)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $invite->delete();
        
        return redirect()->route('admin.invites.index')->with('success', 'Convite removido com sucesso!');
    }

    public function accept($token)
    {
        $invite = Invite::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();
        
        return view('admin.invites.accept', compact('invite'));
    }

    public function storePassword(Request $request, $token)
    {
        $invite = Invite::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();
        
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);
        
        $user = \App\Models\User::create([
            'name' => $invite->name,
            'email' => $invite->email,
            'password' => bcrypt($request->password),
            'role' => $invite->role,
            'company_id' => $invite->company_id,
            'sector_id' => $invite->sector_id,
            'email_verified_at' => now(),
        ]);
        
        $invite->update(['accepted_at' => now()]);
        
        auth()->login($user);
        
        return redirect()->route('dashboard')->with('success', 'Bem-vindo ao HelpDesk! Sua conta foi ativada.');
    }
}