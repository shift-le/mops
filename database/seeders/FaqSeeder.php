<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('FAQ')->truncate();

        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'FAQ_CODE'     => 'FQ' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'FAQ_TITLE'    => '仮のFAQタイトル ' . $i,
                'FAQ_QUESTION' => 'これは仮の質問です。質問番号' . $i . '：システムの挙動は？',
                'FAQ_ANSWER'   => '回答番号' . $i . '：システムはLaravelベースで動作します。',
                'DISP_ORDER'   => $i,
                'HYOJI_FLG'    => 1,
                'DEL_FLG'      => 0,
                'CREATE_DT'    => Carbon::now(),
                'CREATE_APP'   => 'SeederScript',
                'CREATE_USER'  => 'seeder_user',
                'UPDATE_DT'    => Carbon::now(),
                'UPDATE_APP'   => 'SeederScript',
                'UPDATE_USER'  => 'seeder_user',
            ];
        }

        DB::table('FAQ')->insert($data);
    }
}
