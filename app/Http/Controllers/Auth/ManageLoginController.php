<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageLoginController extends Controller
{
    public function show()
    {
        return view('manage.auth.login');
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

            $user = Auth::user();
            if (in_array($user->ROLE_ID, ['MA01', 'NA01'])) {
                return redirect()->route('manage.top');
            } else {
                Auth::logout();
                return back()->with('error', '管理画面の利用権限がありません');
            }
        }

        return back()->with('error', 'ログインに失敗しました');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('manage.login');
    }
}
