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

    // モックユーザー定義
    $mockUsers = [
        'user001' => 'password',
        'admin001' => 'password',
        'nakajima001' => 'password',
    ];

    $inputId = $request->input('USER_ID');
    $inputPass = $request->input('password');

    if (array_key_exists($inputId, $mockUsers)) {
        // モックユーザー認証
        if ($mockUsers[$inputId] !== $inputPass) {
            return back()->with('error', 'パスワードが一致しません（モック）');
        }
        $user = User::where('USER_ID', $inputId)->first();
        if (!$user) {
            return back()->with('error', 'ユーザーが見つかりません（モック）');
        }
        Auth::login($user);
        return redirect('top');
    }

    // 通常ユーザー認証
    $credentials = [
        'USER_ID' => $inputId,
        'password' => $inputPass,
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
