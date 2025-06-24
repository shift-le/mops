<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Faq; // 将来のDB用モデル（今は使わない）
// 優先度を取得してソートするメソッドを入れ込む

class FaqController extends Controller
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
                'FAQ_QUESTION',
                'CREATE_DT'
            )
            ->where('DEL_FLG', 0)
            ;

        // 氏名での絞り込み（部分一致）
        if (!empty($faq)) {
            $query->where('FAQ_TITLE', 'like', "%{$faq}%");
        }

        // ソート
        $query->orderBy($sort, $order);
        // ページネーション（1ページ15件）
        $faqs = $query->paginate(15);
        
        return view('faq.index', compact('faqs', 'faq', 'sort', 'order'));
    }

    public function show($id)
    {
        $faq = DB::table('FAQ')->where('FAQ_CODE', $id)->first();

        if (!$faq) {
            abort(404, 'FAQ not found');
        }

        return view('faq.show', compact('faq'));
    }

}
