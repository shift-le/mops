<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KeijibanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('KEIJIBAN')->insert([
            'KEIJIBAN_CODE'    => 'KB0001',
            'JUYOUDO_STATUS'   => '1',
            'KEISAI_START_DATE'=> Carbon::now()->subDays(1),
            'KEISAI_END_DATE'  => Carbon::now()->addDays(30),
            'KEIJIBAN_TITLE'   => '仮タイトル1',
            'KEIJIBAN_CATEGORY'=> '1',
            'KEIJIBAN_TEXT'    => 'これは仮の掲示板本文です。',
            'HYOJI_FLG'        => 1,
            'DEL_FLG'          => 0,
            'DEL_DT'           => null,
            'CREATE_DT'        => Carbon::now(),
            'CREATE_APP'       => 'SeederScript',
            'CREATE_USER'      => 'seeder_user',
            'UPDATE_DT'        => Carbon::now(),
            'UPDATE_APP'       => 'SeederScript',
            'UPDATE_USER'      => 'seeder_user',
        ]);
    }
}
