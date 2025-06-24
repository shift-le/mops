<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool; // 将来のDB用モデル（今は使わない）
use Illuminate\Support\Facades\DB; // DBファサードを使用
use Illuminate\Support\Facades\Auth; // 認証用ファサード


class BoardController extends Controller
{
    public function index(Request $request)
    {
        // モックデータ（DB接続→コメントアウト）
        $board = $request->query('KEIJBAN');
        $sort = $request->query('sort', 'JUYOUDO_STATUS'); // デフォルトのソートカラム
        $order = $request->query('order', 'asc');   // デフォルトのソート順
        // クエリビルダでUSERSテーブルから取得
        $query = DB::table('KEIJIBAN')
            ->select(
                'KEIJIBAN_CODE',
                'JUYOUDO_STATUS',
                'KEISAI_START_DATE',
                'KEIJIBAN_TITLE',
                'KEIJIBAN_CATEGORY',
                'HYOJI_FLG'  // ★これ追加する！
            )
            ->where('DEL_FLG', 0)
            ->orderBy($sort, $order);

        // ソート
        $query->orderBy($sort, $order);
        // ページネーション（1ページ15件）
        $posts = $query->paginate(15);

        return view('board.index', compact('posts', 'board', 'sort', 'order'));
    }


    public function show($id)
    {
        $board = DB::table('KEIJIBAN')->where('KEIJIBAN_CODE', $id)->first();

        if (!$board) {
            abort(404, 'KEIJIBAN not found');
        }

        return view('board.show', compact('board'));
    }
}
