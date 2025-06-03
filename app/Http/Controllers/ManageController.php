<?php

namespace App\Http\Controllers;

use App\Models\Faq\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
