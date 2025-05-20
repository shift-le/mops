<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'USER_ID' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'USER_ID' => $request->input('USER_ID'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/category');
        }

        return back()->with('error', 'ログインに失敗しました');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
