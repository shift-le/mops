<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ToolType2Seeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $toolType2List = [
            // 共通項目：10番台
            [10, 10, '製品情報概要'],
            [10, 20, '特定情報概要'],
            [10, 30, 'パンフレット・リーフレット'],
            [10, 40, '冊子（医療関係者様向け）'],
            [10, 41, '小冊子（患者様向け）'],
            [10, 50, '使用法'],
            [10, 60, '指導箋'],
            [10, 70, '別冊文献'],
            [10, 80, '下敷き（医師用支援）'],
            [10, 90, '座談会'],
            [10, 100, 'ポスター類'],
            [10, 110, '添付文書・取扱説明書'],
            [10, 120, 'インタビューフォーム'],
            [10, 130, '使用上の注意の解説'],
            [10, 140, 'お知らせ'],
            [10, 150, 'ちらし'],
            [10, 330, '学会・セミナー内容集'],
            [10, 510, 'その他'],

            // 公開用（IDが異なるだけで名称は同じ）
            [11, 11, '製品情報概要'],
            [11, 21, '特定情報概要'],
            [11, 31, 'パンフレット・リーフレット'],
            [11, 45, '冊子（医療関係者様向け）'],
            [11, 46, '小冊子（患者様向け）'],
            [11, 51, '使用法'],
            [11, 61, '指導箋'],
            [11, 71, '別冊文献'],
            [11, 81, '下敷き（医師用支援）'],
            [11, 91, '座談会'],
            [11, 101, 'ポスター類'],
            [11, 111, '添付文書・取扱説明書'],
            [11, 121, 'インタビューフォーム'],
            [11, 131, '使用上の注意の解説'],
            [11, 141, 'お知らせ'],
            [11, 151, 'ちらし'],
            [11, 331, '学会・セミナー内容集'],
            [11, 511, 'その他'],
        ];

        foreach ($toolType2List as $index => [$type1, $type2, $name]) {
            DB::table('TOOL_TYPE2')->updateOrInsert(
                ['TOOL_TYPE1' => $type1, 'TOOL_TYPE2' => $type2],
                [
                    'TOOL_TYPE2_NAME' => $name,
                    'DISPLAY_TURN' => $index + 1,
                    'CREATE_DT' => $now,
                    'CREATE_APP' => 'seeder',
                    'CREATE_USER' => 'seeder',
                    'UPDATE_DT' => $now,
                    'UPDATE_APP' => 'seeder',
                    'UPDATE_USER' => 'seeder',
                ]
            );
        }
    }
}