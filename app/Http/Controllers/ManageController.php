<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 認証用ファサード

class ManageController extends Controller
{
    public function login()
    {
        return view('manage.login');
    }

    public function doLogin(Request $request)
    {

        $credentials = [
            'USER_ID' => $request->input('login_id'),
            'password' => $request->input('password')
        ];

        if (Auth::guard('admins')->attempt($credentials)) {
            // 成功 → TOP画面へ
            return redirect('/manage/top');
        }

        // 失敗 → ログイン画面へ
        return redirect('/manage/login')->with('error', 'ログインIDまたはパスワードが違います');
    }


    public function top()
    {
        return view('manage.top', compact('boards', 'faqs'));
    }

}
