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
            ['id' => 1, 'FAQ_TITLE' => '仮データ１','DISP_ORDER' => '100','CREATE_DT'=> '2025/01/01', 'FAQ_QUESTION' => 'mock1'],
            ['id' => 2, 'FAQ_TITLE' => '仮データ２','DISP_ORDER' => '101','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock2'],
            ['id' => 3, 'FAQ_TITLE' => '仮データ３','DISP_ORDER' => '102','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock3'],
            ['id' => 4, 'FAQ_TITLE' => '仮データ４','DISP_ORDER' => '103','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock4'],
            ['id' => 5, 'FAQ_TITLE' => '仮データ５','DISP_ORDER' => '104','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock5'],
            ['id' => 6, 'FAQ_TITLE' => '仮データ６','DISP_ORDER' => '105','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock6'],
            ['id' => 7, 'FAQ_TITLE' => '仮データ７','DISP_ORDER' => '106','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock7'],
            ['id' => 8, 'FAQ_TITLE' => '仮データ８','DISP_ORDER' => '107','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock8'],
            ['id' => 9, 'FAQ_TITLE' => '仮データ９','DISP_ORDER' => '108','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock9'],
            ['id' => 10, 'FAQ_TITLE' => '仮データ１０','DISP_ORDER' => '109','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock10'],
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
            ['id' => 1, 'FAQ_TITLE' => '仮データ１','DISP_ORDER' => '100','CREATE_DT'=> '2025/01/01', 'FAQ_QUESTION' => 'mock1'],
            ['id' => 2, 'FAQ_TITLE' => '仮データ２','DISP_ORDER' => '101','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock2'],
            ['id' => 3, 'FAQ_TITLE' => '仮データ３','DISP_ORDER' => '102','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock3'],
            ['id' => 4, 'FAQ_TITLE' => '仮データ４','DISP_ORDER' => '103','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock4'],
            ['id' => 5, 'FAQ_TITLE' => '仮データ５','DISP_ORDER' => '104','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock5'],
            ['id' => 6, 'FAQ_TITLE' => '仮データ６','DISP_ORDER' => '105','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock6'],
            ['id' => 7, 'FAQ_TITLE' => '仮データ７','DISP_ORDER' => '106','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock7'],
            ['id' => 8, 'FAQ_TITLE' => '仮データ８','DISP_ORDER' => '107','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock8'],
            ['id' => 9, 'FAQ_TITLE' => '仮データ９','DISP_ORDER' => '108','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock9'],
            ['id' => 10, 'FAQ_TITLE' => '仮データ１０','DISP_ORDER' => '109','CREATE_DT'=> '2025/01/01',  'FAQ_QUESTION' => 'mock10'],
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
            ->sortByDesc('DISP_ORDER')  // 降順に並び替え
            ->values()
            ->all();
    }

}
