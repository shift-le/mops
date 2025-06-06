<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManageLoginController extends Controller
{
    // ログイン画面表示
    public function showLoginForm()
    {
        return view('manage.login');
    }

    // ログイン処理
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

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('manage.login');
    }
}
