<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManageLoginController extends Controller
{
    /**
     * 管理ログインフォーム表示
     */
    public function show()
    {
        return view('manage.login');
    }

    /**
     * ログイン処理
     */
    public function login(Request $request)
    {
        // バリデーション
        $request->validate([
            'USER_ID'  => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('USER_ID', 'password');

        Log::debug('【管理ログイン】ログイン試行', $credentials);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 管理者ロール（MU01, NU01）のみ許可
            if (in_array(strtoupper($user->ROLE_ID), ['MA01', 'NA01'])) {
                Log::debug('【管理ログイン】成功', ['user_id' => $user->USER_ID, 'role' => $user->ROLE_ID]);
                return redirect()->intended('/manage/top');
            }

            // 管理者でない場合は即ログアウト
            Auth::logout();
            Log::warning('【管理ログイン】ロール不正', ['user_id' => $user->USER_ID, 'role' => $user->ROLE_ID]);

            return redirect()->route('managelogin.show')
                ->with('error', '管理者権限がありません。');
        }

        Log::debug('【管理ログイン】認証失敗');

        return redirect()->route('managelogin.show')
            ->with('error', 'ログイン情報が正しくありません。');
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('【管理ログイン】ログアウト完了');

        return redirect()->route('managelogin.show');
    }
}
