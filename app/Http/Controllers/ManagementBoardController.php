<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Keijiban; // 将来のDB用モデル（今は使わない）

class ManagementBoardController extends Controller
{
    public function index(Request $request)
    {
        // モックデータ（DB接続→コメントアウト）
        $board = $request->query('keijiban');
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
    ->orderBy($sort, $order);

        // ソート
        $query->orderBy($sort, $order);
        // ページネーション（1ページ15件）
        $posts = $query->paginate(15);

        return view('manage.managementboard.index', compact('posts', 'board', 'sort', 'order'));
    }

    public function show($id)
    {
        $board = DB::table('KEIJIBAN')->where('KEIJIBAN_CODE', $id)->first();

        if (!$board) {
            abort(404, 'KEIJIBAN not found');
        }

        return view('manage.managementboard.show', compact('board'));
    }

    public function create()
    {
        // 新規作成画面の表示
        return view('manage.managementboard.create');
    }
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'JUYOUDO_STATUS' => 'required|integer',
            'KEISAI_START_DATE' => 'required|date',
            'KEIJIBAN_TITLE' => 'required|string|max:255',
            'KEIJIBAN_TEXT' => 'required|string',
            'KEIJIBAN_CATEGORY' => 'required|integer',
        ]);


        // データの保存（DB接続→コメントアウト）
        DB::table('KEIJIBAN')->insert([
            'JUYOUDO_STATUS' => $request->input('JUYOUDO_STATUS'),
            'KEISAI_START_DATE' => $request->input('KEISAI_START_DATE'),
            'KEIJIBAN_TITLE' => $request->input('KEIJIBAN_TITLE'),
            'KEIJIBAN_CATEGORY' => $request->input('KEIJIBAN_CATEGORY'),
            // 他のカラムも必要に応じて追加
        ]);

        return redirect()->route('managementboard.index')->with('success', '掲示板が作成されました。');
    }
            public function delete($id)
    {
        DB::table('KEIJIBAN')->where('KEIJIBAN_CODE', $id)->delete();

        return redirect()->route('managementboard.index')->with('success', '掲示板項目を削除しました。');
    }
        public function update(Request $request, $id)
    {
        $request->validate([
            'JUYOUDO_STATUS' => 'required|integer',
            'KEISAI_START_DATE' => 'required|date',
            'KEIJIBAN_TITLE' => 'required|string|max:255',
            'KEIJIBAN_TEXT' => 'required|string',
            'KEIJIBAN_CATEGORY' => 'required|integer',
            'HYOJI_FLG' => 'required|boolean',
        ]);

        DB::table('KEIJIBAN')
            ->where('KEIJIBAN_CODE', $id)
            ->update([
                'JUYOUDO_STATUS' => $request->input('JUYOUDO_STATUS'),
                'KEISAI_START_DATE' => $request->input('KEISAI_START_DATE'),
                'KEIJIBAN_TITLE' => $request->input('KEIJIBAN_TITLE'),
                'KEIJIBAN_TEXT' => $request->input('KEIJIBAN_TEXT'),
                'KEIJIBAN_CATEGORY' => $request->input('KEIJIBAN_CATEGORY'),
                'HYOJI_FLG' => $request->input('HYOJI_FLG'),
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'WebApp',
                'UPDATE_USER' => '管理者',
            ]);

        return redirect()->route('managementboard.index')->with('success', '掲示板内容を更新しました。');
    }

}
