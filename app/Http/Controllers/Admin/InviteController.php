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
use App\Mail\InviteMail;

class InviteController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $invites = Invite::with(['company', 'sector'])->orderBy('created_at', 'desc')->paginate(15);
        
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
            'email' => 'required|email|unique:invites,email',
            'role' => 'required|in:admin,technician,user',
            'company_id' => 'required|exists:companies,id',
            'sector_id' => 'required|exists:sectors,id',
        ]);
        
        $invite = Invite::create([
            'email' => $request->email,
            'token' => Str::random(64),
            'role' => $request->role,
            'company_id' => $request->company_id,
            'sector_id' => $request->sector_id,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);
        
        // Enviar email via Mailtrap
        try {
            Mail::to($request->email)->send(new InviteMail($invite));
            return redirect()->route('admin.invites.index')
                ->with('success', '✅ Convite enviado com sucesso para ' . $request->email);
        } catch (\Exception $e) {
            return redirect()->route('admin.invites.index')
                ->with('warning', '⚠️ Convite criado, mas o email não foi enviado. Erro: ' . $e->getMessage());
        }
    }
    
    public function destroy(Invite $invite)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $invite->delete();
        
        return redirect()->route('admin.invites.index')
            ->with('success', 'Convite removido com sucesso!');
    }
    
    public function resend(Invite $invite)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $invite->update([
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
            'status' => 'pending',
        ]);
        
        try {
            Mail::to($invite->email)->send(new InviteMail($invite));
            return redirect()->route('admin.invites.index')
                ->with('success', 'Convite reenviado com sucesso para ' . $invite->email);
        } catch (\Exception $e) {
            return redirect()->route('admin.invites.index')
                ->with('warning', 'Convite reenviado, mas o email falhou: ' . $e->getMessage());
        }
    }
    
    public function cancel(Invite $invite)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $invite->update(['status' => 'expired']);
        
        return redirect()->route('admin.invites.index')
            ->with('success', 'Convite cancelado com sucesso!');
    }
}