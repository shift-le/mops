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
                'TOOL_THUM_FILE' => 'sample1.jpg',
                'TOOL_PDF_FILE'  => 'sample1.pdf',
                'DISPLAY_START_DATE' => now()->subDays(3),
                'HINMEI' => 'H001',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '10',
                'TOOL_TYPE2' => '10',
                'UNIT_TYPE' => '00',
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
                'UNIT_TYPE' => '01',
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
                'TOOL_TYPE2' => '21',
                'UNIT_TYPE' => '02',
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
        DB::table('TOOL')->updateOrInsert(
            ['TOOL_CODE' => 'T004'],
            [
                'TOOL_NAME' => 'アトピー性皮膚炎治療ガイドライン',
                'TOOL_THUM_FILE' => 'tools/thumb/sample4.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample4.pdf',
                'DISPLAY_START_DATE' => now()->subDays(7),
                'HINMEI' => 'H003',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '12',
                'TOOL_TYPE2' => '22',
                'UNIT_TYPE' => '03',
                'TANKA' => '4000',
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
            ['TOOL_CODE' => 'T005'],
            [
                'TOOL_NAME' => '乾癬治療の新常識',
                'TOOL_THUM_FILE' => 'tools/thumb/sample5.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample5.pdf',
                'DISPLAY_START_DATE' => now()->subDays(10),
                'HINMEI' => 'H004',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '13',
                'TOOL_TYPE2' => '23',
                'UNIT_TYPE' => '04',
                'TANKA' => '5000',
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
            ['TOOL_CODE' => 'T006'],
            [
                'TOOL_NAME' => 'アトピー性皮膚炎の最新治療法',
                'TOOL_THUM_FILE' => 'tools/thumb/sample6.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample6.pdf',
                'DISPLAY_START_DATE' => now()->subDays(15),
                'HINMEI' => 'H005',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '14',
                'TOOL_TYPE2' => '24',
                'UNIT_TYPE' => '05',
                'TANKA' => '6000',
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
            ['TOOL_CODE' => 'T007'],
            [
                'TOOL_NAME' => '乾癬の新しい治療法',
                'TOOL_THUM_FILE' => 'tools/thumb/sample7.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample7.pdf',
                'DISPLAY_START_DATE' => now()->subDays(20),
                'HINMEI' => 'H006',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '15',
                'TOOL_TYPE2' => '25',
                'UNIT_TYPE' => '06',
                'TANKA' => '7000',
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
            ['TOOL_CODE' => 'T008'],
            [
                'TOOL_NAME' => 'アトピー性皮膚炎の新しい治療法',
                'TOOL_THUM_FILE' => 'sample8.jpg',
                'TOOL_PDF_FILE'  => 'sample8.pdf',
                'DISPLAY_START_DATE' => now()->subDays(25),
                'HINMEI' => 'H007',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '16',
                'TOOL_TYPE2' => '26',
                'UNIT_TYPE' => '07',
                'TANKA' => '8000',
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
            ['TOOL_CODE' => 'T009'],
            [
                'TOOL_NAME' => '乾癬の新しい治療法②',
                'TOOL_THUM_FILE' => 'sample9.jpg',
                'TOOL_PDF_FILE'  => 'sample9.pdf',
                'DISPLAY_START_DATE' => now()->subDays(30),
                'HINMEI' => 'H008',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '17',
                'TOOL_TYPE2' => '27',
                'UNIT_TYPE' => '08',
                'TANKA' => '9000',
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
            ['TOOL_CODE' => 'T010'],
            [
                'TOOL_NAME' => 'アトピー性皮膚炎の新しい治療法②',
                'TOOL_THUM_FILE' => 'sample10.jpg',
                'TOOL_PDF_FILE'  => 'sample10.pdf',
                'DISPLAY_START_DATE' => now()->subDays(35),
                'HINMEI' => 'H009',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '18',
                'TOOL_TYPE2' => '28',
                'UNIT_TYPE' => '09',
                'TANKA' => '10000',
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
            ['TOOL_CODE' => 'T011'],
            [
                'TOOL_NAME' => '乾癬の新しい治療法③',
                'TOOL_THUM_FILE' => 'tools/thumb/sample11.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample11.pdf',
                'DISPLAY_START_DATE' => now()->subDays(40),
                'HINMEI' => 'H010',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '19',
                'TOOL_TYPE2' => '29',
                'UNIT_TYPE' => '10',
                'TANKA' => '11000',
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
            ['TOOL_CODE' => 'T012'],
            [
                'TOOL_NAME' => 'アトピー性皮膚炎の新しい治療法③',
                'TOOL_THUM_FILE' => 'tools/thumb/sample12.jpg',
                'TOOL_PDF_FILE'  => 'tools/pdf/sample12.pdf',
                'DISPLAY_START_DATE' => now()->subDays(45),
                'HINMEI' => 'H011',
                'RYOIKI' => 'SKIN',
                'TOOL_TYPE1' => '20',
                'TOOL_TYPE2' => '30',
                'UNIT_TYPE' => '11',
                'TANKA' => '12000',
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
