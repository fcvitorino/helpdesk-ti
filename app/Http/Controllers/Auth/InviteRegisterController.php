<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InviteRegisterController extends Controller
{
    public function showRegisterForm($token)
    {
        $invite = Invite::where('token', $token)
                        ->where('status', 'pending')
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$invite) {
            return redirect()->route('login')
                ->with('error', 'Convite inválido, expirado ou já utilizado.');
        }

        return view('auth.invite-register', compact('invite'));
    }

    public function register(Request $request, $token)
    {
        $invite = Invite::where('token', $token)
                        ->where('status', 'pending')
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$invite) {
            return redirect()->route('login')
                ->with('error', 'Convite inválido, expirado ou já utilizado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', new StrongPassword],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $invite->email,
            'password' => Hash::make($request->password),
            'role' => $invite->role,
            'company_id' => $invite->company_id,
            'sector_id' => $invite->sector_id,
            'email_verified_at' => now(),
        ]);

        $invite->update(['status' => 'accepted']);

        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Bem-vindo ao HelpDesk TI! Seu cadastro foi concluído com sucesso.');
    }
}