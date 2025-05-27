<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KeijibanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('KEIJIBAN')->truncate();

        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'KEIJIBAN_CODE'     => 'KB' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'JUYOUDO_STATUS'    => rand(1, 3), // 仮で1〜3のランダム
                'KEISAI_START_DATE' => Carbon::now()->subDays(rand(1, 5)),
                'KEISAI_END_DATE'   => Carbon::now()->addDays(rand(10, 30)),
                'KEIJIBAN_TITLE'    => '仮タイトル ' . $i,
                'KEIJIBAN_CATEGORY' => rand(1, 5), // 仮で1〜5のランダム
                'KEIJIBAN_TEXT'     => 'これは仮の掲示板本文 ' . $i . ' です。',
                'HYOJI_FLG'         => 1,
                'DEL_FLG'           => 0,
                'DEL_DT'            => null,
                'CREATE_DT'         => Carbon::now(),
                'CREATE_APP'        => 'SeederScript',
                'CREATE_USER'       => 'seeder_user',
                'UPDATE_DT'         => Carbon::now(),
                'UPDATE_APP'        => 'SeederScript',
                'UPDATE_USER'       => 'seeder_user',
            ];
        }

        DB::table('KEIJIBAN')->insert($data);
    }
}
