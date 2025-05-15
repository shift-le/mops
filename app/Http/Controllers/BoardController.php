<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $posts = [
            ['id' => 1, 'title' => '掲示板１', 'body' => 'これは掲示板のテスト投稿です。'],
            ['id' => 2, 'title' => '掲示板２', 'body' => 'Laravelの学習状況を共有しましょう！'],
            // 最大10件のモック投稿
        ];

        return view('board.index', compact('posts'));
    }

    public function show($id)
    {
        // 仮の詳細データ
        $post = [
            'id' => $id,
            'title' => "投稿タイトル（ID: $id）",
            'body' => "これは投稿ID $id の詳細表示モックです。",
        ];

        return view('board.show', compact('post'));
    }
}
