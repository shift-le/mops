<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'TOOL_CODE' => 'T001',
                'TOOL_NAME' => 'ヒルドイド説明資料①',
                'TOOL_NAME_KANA' => 'ヒルドイドセツメイシリョウ①',
                'TOOL_THUM_FILE' => 'tools/thumb/sample1.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample1.pdf',
                'DISPLAY_START_DATE' => $now->copy()->subDays(3),
                'HINMEI' => 'H001',
                'RYOIKI' => 'SKIN',
                'SOSHIKI1' => 'マーケティング部',
                'SOSHIKI2' => '資料チーム',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ],
            [
                'TOOL_CODE' => 'T002',
                'TOOL_NAME' => 'ヒルドイド説明資料②',
                'TOOL_NAME_KANA' => 'ヒルドイドセツメイシリョウ②',
                'TOOL_THUM_FILE' => 'tools/thumb/sample2.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample2.pdf',
                'DISPLAY_START_DATE' => $now->copy()->subDays(5),
                'HINMEI' => 'H001',
                'RYOIKI' => 'SKIN',
                'SOSHIKI1' => 'マーケティング部',
                'SOSHIKI2' => '資料チーム',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ],
            [
                'TOOL_CODE' => 'T003',
                'TOOL_NAME' => 'プロトピックパンフレット',
                'TOOL_NAME_KANA' => 'プロトピックパンフレット',
                'TOOL_THUM_FILE' => 'tools/thumb/sample3.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample3.pdf',
                'DISPLAY_START_DATE' => $now->copy()->subDays(2),
                'HINMEI' => 'H002',
                'RYOIKI' => 'SKIN',
                'SOSHIKI1' => 'マーケティング部',
                'SOSHIKI2' => '資料チーム',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ],
        ];

        // さらに7件追加
        for ($i = 4; $i <= 10; $i++) {
            $data[] = [
                'TOOL_CODE' => 'T0' . $i,
                'TOOL_NAME' => '仮ツール資料' . $i,
                'TOOL_NAME_KANA' => 'カリツールシリョウ' . $i,
                'TOOL_THUM_FILE' => 'tools/thumb/sample' . $i . '.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample' . $i . '.pdf',
                'DISPLAY_START_DATE' => $now->copy()->subDays(rand(1, 10)),
                'HINMEI' => 'H00' . rand(1, 3),
                'RYOIKI' => 'SKIN',
                'SOSHIKI1' => 'マーケティング部',
                'SOSHIKI2' => '資料チーム',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ];
        }

        // 挿入実行
        DB::table('TOOL')->insert($data);
    }
}

