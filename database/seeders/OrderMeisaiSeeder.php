<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderMeisaiSeeder extends Seeder
{
    public function run(): void
    {
        $orderCodes = ['ORD0001', 'ORD0002', 'ORD0003'];
        $tools = [
            ['TOOLID' => 'TL001', 'TOOL_NAME' => 'ツールA', 'TANKA' => 2000],
            ['TOOLID' => 'TL002', 'TOOL_NAME' => 'ツールB', 'TANKA' => 3000],
            ['TOOLID' => 'TL003', 'TOOL_NAME' => 'ツールC', 'TANKA' => 4000],
        ];

        foreach ($orderCodes as $orderIndex => $orderCode) {
            foreach ($tools as $toolIndex => $tool) {
                $toolQuantity = rand(1, 5);
                $tanka = $tool['TANKA'];
                $subtotal = $toolQuantity * $tanka;

                $compositeKey = [
                    'TOOL_CODE' => 'TLC' . str_pad($toolIndex + 1, 3, '0', STR_PAD_LEFT) . '_' . $orderCode,
                    'TOOLID' => $tool['TOOLID'],
                ];

                DB::table('ORDER_MEISAI')->updateOrInsert($compositeKey, [
                    'ORDER_CODE'     => $orderCode,
                    'USER_ID'        => 'user' . rand(1, 5),
                    'IRAI_NAME'      => '依頼者' . rand(1, 10),
                    'ORDER_NAME'     => '発注者' . rand(1, 10),
                    'ORDER_ADDRESS'  => '東京都サンプル市サンプル町' . rand(1, 100),
                    'ORDER_PHONE'    => '03-1234-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'ORDER_STATUS'   => rand(0, 1),
                    'TOOL_NAME'      => $tool['TOOL_NAME'],
                    'TANKA'          => $tanka,
                    'AMOUNT'         => $toolQuantity,
                    'TOOL_QUANTITY'  => $toolQuantity,
                    'QUANTITY'       => $toolQuantity,
                    'SUBTOTAL'       => $subtotal,
                    'NOTE'           => '備考メモ' . rand(1, 5),
                    'DEL_FLG'        => 0,
                    'CREATE_DT'      => Carbon::now(),
                    'CREATE_APP'     => 'SeederScript',
                    'CREATE_USER'    => 'SeederUser',
                    'UPDATE_DT'      => Carbon::now(),
                    'UPDATE_APP'     => 'SeederScript',
                    'UPDATE_USER'    => 'SeederUser',
                ]);
            }
        }
    }
}