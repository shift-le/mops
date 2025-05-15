<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Faq; // 将来のDB用モデル（今は使わない）

class FaqController extends Controller
{
    public function index()
    {
        // モックデータ（仮データ）
        $faqs = [
            ['id' => 1, 'title' => '仮データ１', 'question' => 'mock1'],
            ['id' => 2, 'title' => '仮データ２', 'question' => 'mock2'],
            ['id' => 3, 'title' => '仮データ３', 'question' => 'mock3'],
            ['id' => 4, 'title' => '仮データ４', 'question' => 'mock4'],
            ['id' => 5, 'title' => '仮データ５', 'question' => 'mock5'],
            ['id' => 6, 'title' => '仮データ６', 'question' => 'mock6'],
            ['id' => 7, 'title' => '仮データ７', 'question' => 'mock7'],
            ['id' => 8, 'title' => '仮データ８', 'question' => 'mock8'],
            ['id' => 9, 'title' => '仮データ９', 'question' => 'mock9'],
            ['id' => 10, 'title' => '仮データ１０', 'question' => 'mock10'],
            // 追加で仮データを最大10件まで入れる
        ];

        // // DB接続時用（MariaDB）
        // $faqs = Faq::limit(10)->get();

        return view('faq.index', compact('faqs'));
    }

    public function show($id)
    {
        // モックデータ（仮）
        $faq = [
            'id' => $id,
            'title' => '仮タイトル ' . $id,
            'question' => 'これはFAQの詳細表示のテストです（ID: ' . $id . '）',
        ];

        // // DB接続時用
        // $faq = Faq::findOrFail($id);

        return view('faq.show', compact('faq'));
    }
}
