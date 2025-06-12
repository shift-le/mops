<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

public function loginAs(Request $request)
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
        return redirect('top');
    }

    return back()->with('error', 'ログインに失敗しました');
}

    public function logout(Request $request)
    {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
    }
}
