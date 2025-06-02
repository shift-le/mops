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

    $mockIds = ['user001', 'admin001', 'nakajima001'];

    if (in_array($request->input('USER_ID'), $mockIds)) {
        // モックユーザーなら、mockloginAs() 呼び出し
        return $this->mockloginAs($request);
    }

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

public function mockloginAs(Request $request)
{
    $mockUsers = [
        'user001' => 'password',
        'admin001' => 'password',
        'nakajima001' => 'password',
    ];

    $inputId = $request->input('USER_ID');
    $inputPass = $request->input('password');

    if (!array_key_exists($inputId, $mockUsers)) {
        return back()->with('error', '未登録のモックユーザーIDです');
    }

    if ($mockUsers[$inputId] !== $inputPass) {
        return back()->with('error', 'パスワードが一致しません（モック）');
    }

    $user = User::where('USER_ID', $inputId)->first();
    if (!$user) {
        return back()->with('error', 'ユーザーが見つかりません（モック）');
    }

    Auth::login($user);
    return redirect('/cart');
}

    public function logout(Request $request)
    {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
    }
}
