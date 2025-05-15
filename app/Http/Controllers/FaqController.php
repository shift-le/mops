<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Faq; // 将来のDB用モデル（今は使わない）
// 優先度を取得してソートするメソッドを入れ込む

class FaqController extends Controller
{
    public function index()
    {
        // モックデータ（仮データ）
        $faqs = [
            ['id' => 1, 'faq_title' => '仮データ１','disp_order' => '100','create_dt'=> '2025/01/01', 'faq_question' => 'mock1'],
            ['id' => 2, 'faq_title' => '仮データ２','disp_order' => '101','create_dt'=> '2025/01/01',  'faq_question' => 'mock2'],
            ['id' => 3, 'faq_title' => '仮データ３','disp_order' => '102','create_dt'=> '2025/01/01',  'faq_question' => 'mock3'],
            ['id' => 4, 'faq_title' => '仮データ４','disp_order' => '103','create_dt'=> '2025/01/01',  'faq_question' => 'mock4'],
            ['id' => 5, 'faq_title' => '仮データ５','disp_order' => '104','create_dt'=> '2025/01/01',  'faq_question' => 'mock5'],
            ['id' => 6, 'faq_title' => '仮データ６','disp_order' => '105','create_dt'=> '2025/01/01',  'faq_question' => 'mock6'],
            ['id' => 7, 'faq_title' => '仮データ７','disp_order' => '106','create_dt'=> '2025/01/01',  'faq_question' => 'mock7'],
            ['id' => 8, 'faq_title' => '仮データ８','disp_order' => '107','create_dt'=> '2025/01/01',  'faq_question' => 'mock8'],
            ['id' => 9, 'faq_title' => '仮データ９','disp_order' => '108','create_dt'=> '2025/01/01',  'faq_question' => 'mock9'],
            ['id' => 10, 'faq_title' => '仮データ１０','disp_order' => '109','create_dt'=> '2025/01/01',  'faq_question' => 'mock10'],
            // 追加で仮データを最大10件まで入れる
        ];
        // DISP_ORDERが大きい順に並べ替え
        $faqs = $this->sortByDispOrderDesc($faqs);
        return view('faq.index', compact('faqs'));

        // // DB接続時用（MariaDB）
        // $faqs = Faq::limit(10)->get();

        return view('faq.index', compact('faqs'));
    }

    public function show($id)
    {
        // モックデータ（仮データ）
        $faqs = [
            ['id' => 1, 'faq_title' => '仮データ１','disp_order' => '100','create_dt'=> '2025/01/01', 'faq_question' => 'mock1'],
            ['id' => 2, 'faq_title' => '仮データ２','disp_order' => '101','create_dt'=> '2025/01/01',  'faq_question' => 'mock2'],
            ['id' => 3, 'faq_title' => '仮データ３','disp_order' => '102','create_dt'=> '2025/01/01',  'faq_question' => 'mock3'],
            ['id' => 4, 'faq_title' => '仮データ４','disp_order' => '103','create_dt'=> '2025/01/01',  'faq_question' => 'mock4'],
            ['id' => 5, 'faq_title' => '仮データ５','disp_order' => '104','create_dt'=> '2025/01/01',  'faq_question' => 'mock5'],
            ['id' => 6, 'faq_title' => '仮データ６','disp_order' => '105','create_dt'=> '2025/01/01',  'faq_question' => 'mock6'],
            ['id' => 7, 'faq_title' => '仮データ７','disp_order' => '106','create_dt'=> '2025/01/01',  'faq_question' => 'mock7'],
            ['id' => 8, 'faq_title' => '仮データ８','disp_order' => '107','create_dt'=> '2025/01/01',  'faq_question' => 'mock8'],
            ['id' => 9, 'faq_title' => '仮データ９','disp_order' => '108','create_dt'=> '2025/01/01',  'faq_question' => 'mock9'],
            ['id' => 10, 'faq_title' => '仮データ１０','disp_order' => '109','create_dt'=> '2025/01/01',  'faq_question' => 'mock10'],
            // 追加で仮データを最大10件まで入れる
        ];
            // 該当のFAQを検索
        $faq = collect($faqs)->firstWhere('id', (int)$id); 
            if (!$faq) {
        abort(404, 'FAQ not found');
    }

        return view('faq.show', compact('faq'));
        // // DB接続時用
        // $faq = Faq::findOrFail($id);
    }
    private function sortByDispOrderDesc(array $faqs): array
{
    return collect($faqs)
        ->sortByDesc('disp_order')  // 降順に並び替え
        ->values()
        ->all();
}

}
