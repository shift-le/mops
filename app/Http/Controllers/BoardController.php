<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        // モックデータ（DB接続→コメントアウト）
        $posts = [
            [
                'id' => 1,
                'JUYOUDO_STATUS' => '通常',
                'KEISAI_START_DATE' => '2025-05-01',
                'KEIJIBAN_TITLE' => '【重要】メンテナンスのお知らせ',
                'KEIJIBAN_CATEGORY' => 'GUIDE'
            ],
            [
                'id' => 2,
                'JUYOUDO_STATUS' => '緊急',
                'KEISAI_START_DATE' => '2025-05-10',
                'KEIJIBAN_TITLE' => 'Laravel勉強会のお知らせ',
                'KEIJIBAN_CATEGORY' => 'GUIDE'
            ],
            [
                'id' => 3,
                'JUYOUDO_STATUS' => '通常',
                'KEISAI_START_DATE' => '2025-05-15',
                'KEIJIBAN_TITLE' => '開発メモ共有スレッド',
                'KEIJIBAN_CATEGORY' => 'GUIDE'
            ],
            // 必要に応じて最大10件まで追加可能
        ];

        return view('board.index', compact('posts'));
    }

    public function show($id)
    {
        // 仮の詳細データ（実際はDBから取得予定）
        $post = [
            'id' => $id,
            'JUYOUDO_STATUS' => '中',
            'KEISAI_START_DATE' => '2025-05-10',
            'KEIJIBAN_TITLE' => "投稿タイトル",
        ];

        return view('board.show', compact('post'));
    }
}
