<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('TOOL')->updateOrInsert(
            ['TOOL_CODE' => 'T001'],
            [
                'TOOL_NAME' => 'ヒルドイド説明資料①',
                'TOOL_THUM_FILE' => 'tools/thumb/sample1.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample1.pdf',
                'DISPLAY_START_DATE' => now()->subDays(3),
                'HINMEI' => 'H001',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '10',
                'TOOL_TYPE2' => '10',
                'TANKA' => '1000',
                'DEL_FLG' => 0,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ]
        );

        DB::table('TOOL')->updateOrInsert(
            ['TOOL_CODE' => 'T002'],
            [
                'TOOL_NAME' => 'ヒルドイド説明資料②',
                'TOOL_THUM_FILE' => 'tools/thumb/sample2.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample2.pdf',
                'DISPLAY_START_DATE' => now()->subDays(5),
                'HINMEI' => 'H001',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '10',
                'TOOL_TYPE2' => '11',
                'TANKA' => '2000',
                'DEL_FLG' => 0,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ]
        );

        DB::table('TOOL')->updateOrInsert(
            ['TOOL_CODE' => 'T003'],
            [
                'TOOL_NAME' => 'プロトピックパンフレット',
                'TOOL_THUM_FILE' => 'tools/thumb/sample3.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample3.pdf',
                'DISPLAY_START_DATE' => now()->subDays(3),
                'HINMEI' => 'H002',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '11',
                'TOOL_TYPE2' => '11',
                'TANKA' => '3000',
                'DEL_FLG' => 0,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ]
        );
    }
}
