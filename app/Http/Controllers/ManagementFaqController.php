<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 認証用ファサード
use App\Models\Faq; // 将来のDB用モデル（今は使わない）
use Illuminate\Support\Facades\Log; // ログ出力用ファサード
// 優先度を取得してソートするメソッドを入れ込む

class ManagementFaqController extends Controller
{
    public function index(Request $request)
    {

        // クエリパラメータの取得
        $faq = $request->query('faq');
        $sort = $request->query('sort', 'DISP_ORDER'); // デフォルトのソートカラム
        $order = $request->query('order', 'desc');   // デフォルトのソート順

        // クエリビルダでUSERSテーブルから取得
        $query = DB::table('FAQ')
            ->select(
                'FAQ_CODE',
                'FAQ_TITLE',
                'DISP_ORDER',
                'HYOJI_FLG', // 表示フラグ
                'FAQ_QUESTION'
            );

        // 氏名での絞り込み（部分一致）
        if (!empty($faq)) {
            $query->where('FAQ_TITLE', 'like', "%{$faq}%");
        }

        // ソート
        $query->orderBy($sort, $order);
        // ページネーション（1ページ15件）
        $faqs = $query->paginate(15);

        // ログ出力
        Log::debug('【管理】FAQ一覧取得', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'sort' => $sort,
            'order' => $order,
            'faqs_count' => $faqs->count(),
        ]);
        
        return view('manage.managementfaq.index', compact('faqs', 'faq', 'sort', 'order'));
    }

    
    public function show($id)
    {
        $faq = DB::table('FAQ')->where('FAQ_CODE', $id)->first();

        if (!$faq) {
            abort(404, 'FAQ not found');
        }

        // ログ出力
        Log::debug('【管理】FAQ詳細取得', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'FAQ_CODE' => $id,
            'faq' => $faq,
        ]);
        return view('manage.managementfaq.show', compact('faq'));
    }


    public function create()
    {
        Log::debug('【管理】FAQ新規作成画面表示');
        // 新規作成画面の表示
        return view('manage.managementfaq.create');
    }


    public function delete($id)
    {
        DB::table('FAQ')->where('FAQ_CODE', $id)->delete();

        // ログ出力
        Log::debug('【管理】FAQ削除', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'FAQ_CODE' => $id,
        ]);
        return redirect()->route('managementfaq.index')->with('success', 'FAQを削除しました。');
    }


    public function store(Request $request)
    {
        // 最大の番号を取得（FQ0001 → 0001部分を取得して数値化）
        $maxCode = DB::table('FAQ')
            ->select(DB::raw('MAX(CAST(SUBSTRING(FAQ_CODE, 3) AS UNSIGNED)) as max_code'))
            ->value('max_code');

        // 次の番号を決定
        $nextCodeNum = $maxCode ? $maxCode + 1 : 1;

        // FQ＋ゼロパディング4桁
        $nextFaqCode = 'FQ' . str_pad($nextCodeNum, 4, '0', STR_PAD_LEFT);
        // バリデーション
        $request->validate([
            'FAQ_TITLE'    => 'required|string|max:255',
            'FAQ_QUESTION' => 'required|string',
            'DISP_ORDER'   => 'required|integer',
            'HYOJI_FLG'    => 'required|boolean',
        ]);

        // データ登録
        DB::table('FAQ')->insert([
            'FAQ_CODE'     => $nextFaqCode,
            'FAQ_TITLE'    => $request->input('FAQ_TITLE'),
            'FAQ_QUESTION' => $request->input('FAQ_QUESTION'),
            'FAQ_ANSWER'   => '', // ★ここ追加
            'DISP_ORDER'   => $request->input('DISP_ORDER'),
            'HYOJI_FLG'    => $request->input('HYOJI_FLG'),
            'DEL_FLG'      => 0,
            'CREATE_DT'    => now(),
            'CREATE_APP'   => 'Mops',
            'CREATE_USER'  => '管理者',
            'UPDATE_DT'    => now(),
            'UPDATE_APP'   => 'Mops',
            'UPDATE_USER'  => '管理者',
        ]);

        // ログ出力
        Log::debug('【管理】FAQ新規登録', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'FAQ_CODE' => $nextFaqCode,
            'FAQ_TITLE' => $request->input('FAQ_TITLE'),
            'DISP_ORDER' => $request->input('DISP_ORDER'),
            'HYOJI_FLG' => $request->input('HYOJI_FLG'),
        ]);
        return redirect()->route('managementfaq.index')->with('success', 'FAQを登録しました。');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'FAQ_TITLE'    => 'required|string|max:255',
            'FAQ_QUESTION' => 'required|string',
            'DISP_ORDER'   => 'required|integer',
            'HYOJI_FLG'    => 'required|boolean',
        ]);

        DB::table('FAQ')
            ->where('FAQ_CODE', $id)
            ->update([
                'FAQ_TITLE'    => $request->input('FAQ_TITLE'),
                'FAQ_QUESTION' => $request->input('FAQ_QUESTION'),
                'DISP_ORDER'   => $request->input('DISP_ORDER'),
                'HYOJI_FLG'    => $request->input('HYOJI_FLG'),
                'UPDATE_DT'    => now(),
                'UPDATE_APP'   => 'WebApp',
                'UPDATE_USER'  => '管理者',
            ]);

        // ログ出力
        Log::debug('【管理】FAQ更新', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'FAQ_CODE' => $id,
            'FAQ_TITLE' => $request->input('FAQ_TITLE'),
            'DISP_ORDER' => $request->input('DISP_ORDER'),
            'HYOJI_FLG' => $request->input('HYOJI_FLG'),
        ]);
        return redirect()->route('managementfaq.index')->with('success', 'FAQを更新しました。');
    }


        public function confirm(Request $request)
    {
        $validated = $request->validate([
            'DISP_ORDER' => 'required|integer',
            'FAQ_TITLE' => 'required|string',
            'FAQ_QUESTION' => 'required|string',
            'HYOJI_FLG' => 'required|in:0,1',
        ]);

        // 確認画面の表示
        Log::debug('【管理】FAQ確認画面表示', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'input' => $validated,
        ]);
        return view('manage.managementfaq.confirm', ['input' => $validated]);
    }

}
