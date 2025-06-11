<?php

namespace App\Http\Controllers;

use App\Models\Faq\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // 掲示板の新着5件
        $boards = DB::table('KEIJIBAN')
            ->where('DEL_FLG', 0)
            ->orderBy('KEISAI_START_DATE', 'desc')
            ->limit(9)
            ->get();

        // FAQの新着5件
        $faqs = DB::table('FAQ')
            ->where('DEL_FLG', 0)
            ->orderBy('CREATE_DT', 'desc')
            ->limit(5)
            ->get();

        return view('manage.top', compact('boards', 'faqs'));
    }

}
