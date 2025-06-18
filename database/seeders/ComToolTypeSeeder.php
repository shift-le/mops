<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComToolTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

$data = [
    [1, '製品情報概要'],
    [2, '特定情報概要'],
    [3, 'パンフレット・リーフレット'],
    [4, '冊子（医療関係者様向け）'],
    [5, '小冊子（患者様向け）'],
    [6, '使用法'],
    [7, '指導箋'],
    [8, '別冊文献'],
    [9, '下敷き（医師用支援）'],
    [10, '座談会'],
    [11, 'ポスター類'],
    [12, '添付文書・取扱説明書'],
    [13, 'インタビューフォーム'],
    [14, '使用上の注意の解説'],
    [15, 'お知らせ'],
    [16, 'ちらし'],
    [17, '学会・セミナー内容集'],
    [18, 'その他'],
];

        foreach ($data as $i => [$code, $name]) {
            DB::table('M_COM_TOOL_TYPE')->updateOrInsert(
                ['COM_TOOL_TYPE' => $code],
                [
                    'COM_TOOL_TYPE_NAME' => $name,
                    'DISP_ORDER' => $i + 1,
                    'CREATE_DT' => $now,
                    'CREATE_APP' => 'SeederApp',
                    'CREATE_USER' => 'SeederUser',
                    'UPDATE_DT' => $now,
                    'UPDATE_APP' => 'SeederApp',
                    'UPDATE_USER' => 'SeederUser',
                ]
            );
        }
    }
}
