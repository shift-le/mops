<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('cart')->insert([
            [
                'USER_ID' => 'user001',
                'TOOL_CODE' => 'T003',
                'QUANTITY' => 2,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'Seeder',
                'CREATE_USER' => 'SeederUser',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'Seeder',
                'UPDATE_USER' => 'SeederUser',
            ],
            [
                'USER_ID' => 'user001',
                'TOOL_CODE' => 'T005',
                'QUANTITY' => 1,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'Seeder',
                'CREATE_USER' => 'SeederUser',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'Seeder',
                'UPDATE_USER' => 'SeederUser',
            ]
        ]);
    }
}
