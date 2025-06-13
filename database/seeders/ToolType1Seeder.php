<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ToolType1Seeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'TOOL_TYPE1' => 10,
                'TOOL_TYPE1_NAME' => '宣伝用印刷物',
                'DISP_ORDER' => 1,
            ],
            [
                'TOOL_TYPE1' => 11,
                'TOOL_TYPE1_NAME' => '(公開)宣伝用印刷物',
                'DISP_ORDER' => 2,
            ],
        ];

        foreach ($data as $item) {
            DB::table('M_TOOL_TYPE1')->updateOrInsert(
                ['TOOL_TYPE1' => $item['TOOL_TYPE1']],
                array_merge($item, [
                    'CREATE_DT' => $now,
                    'CREATE_APP' => 'seeder',
                    'CREATE_USER' => 'seeder',
                    'UPDATE_DT' => $now,
                    'UPDATE_APP' => 'seeder',
                    'UPDATE_USER' => 'seeder',
                ])
            );
        }
    }
}