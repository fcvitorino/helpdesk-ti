<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Rules\StrongPassword;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', new StrongPassword],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}