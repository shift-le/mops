<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RyoikiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('RYOIKI')->updateOrInsert(
            ['RYOIKI_CODE' => 'skin', 'HINMEI_CODE' => 'A001'],
            [
                'RYOIKI_NAME' => '皮膚',
                'DISP_ORDER' => 1,
                'QUANTITY' => 100,
                'ACTION_FLG' => 1,
                'DEL_FLG' => 0,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'admin',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'admin',
            ]
        );

        DB::table('RYOIKI')->updateOrInsert(
            ['RYOIKI_CODE' => 'resp', 'HINMEI_CODE' => 'A002'],
            [
                'RYOIKI_NAME' => '呼吸器',
                'DISP_ORDER' => 2,
                'QUANTITY' => 50,
                'ACTION_FLG' => 1,
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
