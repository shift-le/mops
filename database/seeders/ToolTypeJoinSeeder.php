<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ToolTypeJoinSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $mappings = [
            [10, 10, '1'], [11, 11, '1'],
            [10, 20, '2'], [11, 21, '2'],
            [10, 30, '3'], [11, 31, '3'],
            [10, 40, '4'], [11, 45, '4'],
            [10, 41, '5'], [11, 46, '5'],
            [10, 50, '6'], [11, 51, '6'],
            [10, 60, '7'], [11, 61, '7'],
            [10, 70, '8'], [11, 71, '8'],
            [10, 80, '9'], [11, 81, '9'],
            [10, 90, '10'], [11, 91, '10'],
            [10, 100, '11'], [11, 101, '11'],
            [10, 110, '12'], [11, 111, '12'],
            [10, 120, '13'], [11, 121, '13'],
            [10, 130, '14'], [11, 131, '14'],
            [10, 140, '15'], [11, 141, '15'],
            [10, 150, '16'], [11, 151, '16'],
            [10, 330, '17'], [11, 331, '17'],
            [10, 510, '18'], [11, 511, '18'],
        ];

        foreach ($mappings as [$t1, $t2, $common]) {
            DB::table('M_TOOL_TYPE_JOIN')->updateOrInsert(
                ['TOOL_TYPE1' => $t1, 'TOOL_TYPE2' => $t2],
                [
                    'COMMON_TYPE' => $common,
                    'CREATE_DT' => $now,
                    'CREATE_APP' => 'SeederApp',
                    'CREATE_USER' => 'SeederUser',
                    'UPDATE_DT' => $now,
                    'UPDATE_APP' => 'SeederApp',
                    'UPDATE_USER' => 'SeederUser',
                ]
            );
        }
    }
}
