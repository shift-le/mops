<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageController extends Controller
{
    public function login()
    {
        return view('manage.login');
    }

    public function doLogin(Request $request)
    {
        $loginId = $request->input('login_id');
        $password = $request->input('password');

        if ($loginId === 'mops' && $password === 'mops') {
            // 成功 → TOP画面に遷移
            return redirect(url('/manage/top'));
        } else {
            // 失敗 → ログイン画面に戻す（エラー表示も仮）
            return redirect('/manage/login')->with('error', 'ログインIDまたはパスワードが違います');
        }
    }

    public function top()
    {
        return view('manage.top');
    }
}
