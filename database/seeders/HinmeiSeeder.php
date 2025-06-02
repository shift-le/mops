<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HinmeiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hinmei')->truncate();

        DB::table('hinmei')->insert([
            [
                'HINMEI_CODE' => 'H001',
                'HINMEI_NAME' => 'ヒルドイド群',
                'RYOIKI_CODE' => 'skin',
                'ACTION_FLG' => 1,
                'DEL_FLG' => 0,
                'DISP_ORDER' => 1,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ],
            [
                'HINMEI_CODE' => 'H002',
                'HINMEI_NAME' => 'プロトピック',
                'RYOIKI_CODE' => 'skin',
                'ACTION_FLG' => 1,
                'DEL_FLG' => 0,
                'DISP_ORDER' => 2,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ],
            [
                'HINMEI_CODE' => 'R001',
                'HINMEI_NAME' => 'アストモリジン',
                'RYOIKI_CODE' => 'resp',
                'ACTION_FLG' => 1,
                'DEL_FLG' => 0,
                'DISP_ORDER' => 1,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ],
        ]);
    }
}
