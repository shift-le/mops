<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('FAQ')->insert([
            'FAQ_CODE'     => 'FQ0001',
            'FAQ_TITLE'    => '仮のFAQタイトル',
            'FAQ_QUESTION' => 'これは仮の質問です。システムはどのように動作しますか？',
            'FAQ_ANSWER'   => 'システムはLaravelベースで動作しています。',
            'DISP_ORDER'   => 1,
            'HYOJI_FLG'    => 1,
            'DEL_FLG'      => 0,
            'CREATE_DT'    => Carbon::now(),
            'CREATE_APP'   => 'SeederScript',
            'CREATE_USER'  => 'seeder_user',
            'UPDATE_DT'    => Carbon::now(),
            'UPDATE_APP'   => 'SeederScript',
            'UPDATE_USER'  => 'seeder_user',
        ]);
    }
}
