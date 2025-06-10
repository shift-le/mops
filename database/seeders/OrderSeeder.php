<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ORDER')->truncate();

        $users = ['admin001', 'user001'];

        for ($i = 1; $i <= 20; $i++) {
            DB::table('ORDER')->insert([
                'ORDER_CODE'        => 'ORD' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'ORDER_STATUS'      => '1',
                'HASSOUSAKI_CODE'   => 'HS' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'USER_ID'           => $users[array_rand($users)],
                'IRAI_NAME'         => '依頼者' . $i,
                'ORDER_NAME'        => '発注者' . $i,
                'ORDER_ADDRESS'     => '住所' . $i,
                'ORDER_PHONE'       => '080-0000-00' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'ORDER_STATUS2'     => '0',
                'ORDER_TOOLID'      => 'TL' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'AMOUNT'            => rand(1, 10),
                'SUBTOTAL'          => rand(1000, 10000),
                'DEL_FLG'           => 0,
                'CREATE_DT'         => Carbon::now(),
                'CREATE_APP'        => 'SeederScript',
                'CREATE_USER'       => 'seeder_user',
                'UPDATE_DT'         => Carbon::now(),
                'UPDATE_APP'        => 'SeederScript',
                'UPDATE_USER'       => 'seeder_user',
            ]);
        }
    }
}
