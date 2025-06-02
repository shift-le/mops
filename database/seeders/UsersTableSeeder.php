<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('USERS')->updateOrInsert(
            ['USER_ID' => 'user001'],
            [
                'UPDATE_FLG' => '1',
                'SHAIN_ID' => 'emp001',
                'NAME' => '利用者 太郎',
                'NAME_KANA' => 'リヨウシャ タロウ',
                'PASSWORD' => Hash::make('admin'),
                'EMAIL' => 'user1@example.com',
                'MOBILE_TEL' => '09000000001',
                'MOBILE_EMAIL' => 'user1@mobile.com',
                'SHITEN_BU_CODE' => '101',
                'EIGYOSHO_GROUP_CODE' => '10001',
                'ROLE_ID' => 'MU01',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'SeederUser',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'SeederUser',
            ]
        );

        DB::table('USERS')->updateOrInsert(
            ['USER_ID' => 'admin001'],
            [
                'UPDATE_FLG' => '1',
                'SHAIN_ID' => 'emp002',
                'NAME' => 'マルホ 管理者',
                'NAME_KANA' => 'マルホ カンリシャ',
                'password' => Hash::make('password'),
                'EMAIL' => 'admin1@example.com',
                'MOBILE_TEL' => '09000000002',
                'MOBILE_EMAIL' => 'admin1@mobile.com',
                'SHITEN_BU_CODE' => '102',
                'EIGYOSHO_GROUP_CODE' => '10002',
                'ROLE_ID' => 'MA01',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'SeederUser',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'SeederUser',
            ]
        );

        DB::table('USERS')->updateOrInsert(
            ['USER_ID' => 'nakajima001'],
            [
                'UPDATE_FLG' => '1',
                'SHAIN_ID' => 'emp003',
                'NAME' => '中島 管理者',
                'NAME_KANA' => 'ナカジマ カンリシャ',
                'password' => Hash::make('password'),
                'EMAIL' => 'nakajima@example.com',
                'MOBILE_TEL' => '09000000003',
                'MOBILE_EMAIL' => 'nakajima@mobile.com',
                'SHITEN_BU_CODE' => 'B003',
                'EIGYOSHO_GROUP_CODE' => 'G003',
                'ROLE_ID' => 'NA01',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'SeederUser',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'SeederUser',
            ],
        );

        // ダミーユーザー15件追加
        for ($i = 4; $i <= 18; $i++) {
            $data[] = [
                'USER_ID' => sprintf('user%03d', $i),
                'UPDATE_FLG' => '1',
                'SHAIN_ID' => sprintf('emp%03d', $i),
                'NAME' => "利用者 {$i}号",
                'NAME_KANA' => "リヨウシャ {$i}ゴウ",
                'PASSWORD' => "admin",
                'EMAIL' => "user{$i}@example.com",
                'MOBILE_TEL' => '0900000' . sprintf('%04d', $i),
                'MOBILE_EMAIL' => "user{$i}@mobile.com",
                'SHITEN_BU_CODE' => 'B001',
                'EIGYOSHO_GROUP_CODE' => 'G001',
                'ROLE_ID' => 'MU01',
                'DEL_FLG' => 0,
                'CREATE_DT' => $now,
                'CREATE_APP' => 'SeederApp',
                'CREATE_USER' => 'SeederUser',
                'UPDATE_DT' => $now,
                'UPDATE_APP' => 'SeederApp',
                'UPDATE_USER' => 'SeederUser',
            ];
        }

        // 一括登録
        DB::table('USERS')->insert($data);
    }

}
