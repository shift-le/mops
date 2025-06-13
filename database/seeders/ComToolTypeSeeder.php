<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComToolTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'COM_TOOL_TYPE' => 1,
                'COM_TOOL_TYPE_NAME' => '製品情報概要',
                'DISP_ORDER' => 1,
            ],
            [
                'COM_TOOL_TYPE' => 2,
                'COM_TOOL_TYPE_NAME' => '特定情報概要',
                'DISP_ORDER' => 2,
            ],
            [
                'COM_TOOL_TYPE' => 3,
                'COM_TOOL_TYPE_NAME' => 'パンフレット・リーフレット',
                'DISP_ORDER' => 3,
            ],
        ];

        foreach ($data as $item) {
            DB::table('M_COM_TOOL_TYPE')->updateOrInsert(
                ['COM_TOOL_TYPE' => $item['COM_TOOL_TYPE']],
                array_merge($item, [
                    'CREATE_DT' => $now,
                    'CREATE_APP' => 'SeederApp',
                    'CREATE_USER' => 'SeederUser',
                    'UPDATE_DT' => $now,
                    'UPDATE_APP' => 'SeederApp',
                    'UPDATE_USER' => 'SeederUser',
                ])
            );
        }
    }
}