<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('tube.index');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'matricule' => 'required|string',
        ]);

        $matricule = $request->input('matricule');

        $user = \App\Models\User::where('matricule', $matricule)->first();

        if ($user) {
            Auth::login($user);

            $request->session()->regenerate();

            if ($user->role==='admin') {
                return redirect()->route('admin.index');
            }else{
                return redirect()->route('tube.index');
            }

        }

        return back()->withErrors([
            'matricule' => 'Matricule invalide.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
