<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneralTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $records = [
            // --- 都道府県 ---
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '01', 'VALUE' => '北海道', 'VALUE_KANA' => 'ホッカイドウ', 'VALUE_ENGLISH' => 'Hokkaido', 'DISP_ORDER' => 1],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '02', 'VALUE' => '青森県', 'VALUE_KANA' => 'アオモリケン', 'VALUE_ENGLISH' => 'Aomori', 'DISP_ORDER' => 2],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '03', 'VALUE' => '岩手県', 'VALUE_KANA' => 'イワテケン', 'VALUE_ENGLISH' => 'Iwate', 'DISP_ORDER' => 3],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '04', 'VALUE' => '宮城県', 'VALUE_KANA' => 'ミヤギケン', 'VALUE_ENGLISH' => 'Miyagi', 'DISP_ORDER' => 4],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '05', 'VALUE' => '秋田県', 'VALUE_KANA' => 'アキタケン', 'VALUE_ENGLISH' => 'Akita', 'DISP_ORDER' => 5],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '06', 'VALUE' => '山形県', 'VALUE_KANA' => 'ヤマガタケン', 'VALUE_ENGLISH' => 'Yamagata', 'DISP_ORDER' => 6],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '07', 'VALUE' => '福島県', 'VALUE_KANA' => 'フクシマケン', 'VALUE_ENGLISH' => 'Fukushima', 'DISP_ORDER' => 7],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '08', 'VALUE' => '茨城県', 'VALUE_KANA' => 'イバラキケン', 'VALUE_ENGLISH' => 'Ibaraki', 'DISP_ORDER' => 8],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '09', 'VALUE' => '栃木県', 'VALUE_KANA' => 'トチギケン', 'VALUE_ENGLISH' => 'Tochigi', 'DISP_ORDER' => 9],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '10', 'VALUE' => '群馬県', 'VALUE_KANA' => 'グンマケン', 'VALUE_ENGLISH' => 'Gunma', 'DISP_ORDER' => 10],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '11', 'VALUE' => '埼玉県', 'VALUE_KANA' => 'サイタマケン', 'VALUE_ENGLISH' => 'Saitama', 'DISP_ORDER' => 11],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '12', 'VALUE' => '千葉県', 'VALUE_KANA' => 'チバケン', 'VALUE_ENGLISH' => 'Chiba', 'DISP_ORDER' => 12],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '13', 'VALUE' => '東京都', 'VALUE_KANA' => 'トウキョウト', 'VALUE_ENGLISH' => 'Tokyo', 'DISP_ORDER' => 13],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '14', 'VALUE' => '神奈川県', 'VALUE_KANA' => 'カナガワケン', 'VALUE_ENGLISH' => 'Kanagawa', 'DISP_ORDER' => 14],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '15', 'VALUE' => '新潟県', 'VALUE_KANA' => 'ニイガタケン', 'VALUE_ENGLISH' => 'Niigata', 'DISP_ORDER' => 15],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '16', 'VALUE' => '富山県', 'VALUE_KANA' => 'トヤマケン', 'VALUE_ENGLISH' => 'Toyama', 'DISP_ORDER' => 16],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '17', 'VALUE' => '石川県', 'VALUE_KANA' => 'イシカワケン', 'VALUE_ENGLISH' => 'Ishikawa', 'DISP_ORDER' => 17],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '18', 'VALUE' => '福井県', 'VALUE_KANA' => 'フクイケン', 'VALUE_ENGLISH' => 'Fukui', 'DISP_ORDER' => 18],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '19', 'VALUE' => '山梨県', 'VALUE_KANA' => 'ヤマナシケン', 'VALUE_ENGLISH' => 'Yamanashi', 'DISP_ORDER' => 19],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '20', 'VALUE' => '長野県', 'VALUE_KANA' => 'ナガノケン', 'VALUE_ENGLISH' => 'Nagano', 'DISP_ORDER' => 20],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '21', 'VALUE' => '岐阜県', 'VALUE_KANA' => 'ギフケン', 'VALUE_ENGLISH' => 'Gifu', 'DISP_ORDER' => 21],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '22', 'VALUE' => '静岡県', 'VALUE_KANA' => 'シズオカケン', 'VALUE_ENGLISH' => 'Shizuoka', 'DISP_ORDER' => 22],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '23', 'VALUE' => '愛知県', 'VALUE_KANA' => 'アイチケン', 'VALUE_ENGLISH' => 'Aichi', 'DISP_ORDER' => 23],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '24', 'VALUE' => '三重県', 'VALUE_KANA' => 'ミエケン', 'VALUE_ENGLISH' => 'Mie', 'DISP_ORDER' => 24],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '25', 'VALUE' => '滋賀県', 'VALUE_KANA' => 'シガケン', 'VALUE_ENGLISH' => 'Shiga', 'DISP_ORDER' => 25],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '26', 'VALUE' => '京都府', 'VALUE_KANA' => 'キョウトフ', 'VALUE_ENGLISH' => 'Kyoto', 'DISP_ORDER' => 26],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '27', 'VALUE' => '大阪府', 'VALUE_KANA' => 'オオサカフ', 'VALUE_ENGLISH' => 'Osaka', 'DISP_ORDER' => 27],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '28', 'VALUE' => '兵庫県', 'VALUE_KANA' => 'ヒョウゴケン', 'VALUE_ENGLISH' => 'Hyogo', 'DISP_ORDER' => 28],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '29', 'VALUE' => '奈良県', 'VALUE_KANA' => 'ナラケン', 'VALUE_ENGLISH' => 'Nara', 'DISP_ORDER' => 29],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '30', 'VALUE' => '和歌山県', 'VALUE_KANA' => 'ワカヤマケン', 'VALUE_ENGLISH' => 'Wakayama', 'DISP_ORDER' => 30],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '31', 'VALUE' => '鳥取県', 'VALUE_KANA' => 'トットリケン', 'VALUE_ENGLISH' => 'Tottori', 'DISP_ORDER' => 31],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '32', 'VALUE' => '島根県', 'VALUE_KANA' => 'シマネケン', 'VALUE_ENGLISH' => 'Shimane', 'DISP_ORDER' => 32],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '33', 'VALUE' => '岡山県', 'VALUE_KANA' => 'オカヤマケン', 'VALUE_ENGLISH' => 'Okayama', 'DISP_ORDER' => 33],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '34', 'VALUE' => '広島県', 'VALUE_KANA' => 'ヒロシマケン', 'VALUE_ENGLISH' => 'Hiroshima', 'DISP_ORDER' => 34],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '35', 'VALUE' => '山口県', 'VALUE_KANA' => 'ヤマグチケン', 'VALUE_ENGLISH' => 'Yamaguchi', 'DISP_ORDER' => 35],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '36', 'VALUE' => '徳島県', 'VALUE_KANA' => 'トクシマケン', 'VALUE_ENGLISH' => 'Tokushima', 'DISP_ORDER' => 36],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '37', 'VALUE' => '香川県', 'VALUE_KANA' => 'カガワケン', 'VALUE_ENGLISH' => 'Kagawa', 'DISP_ORDER' => 37],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '38', 'VALUE' => '愛媛県', 'VALUE_KANA' => 'エヒメケン', 'VALUE_ENGLISH' => 'Ehime', 'DISP_ORDER' => 38],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '39', 'VALUE' => '高知県', 'VALUE_KANA' => 'コウチケン', 'VALUE_ENGLISH' => 'Kochi', 'DISP_ORDER' => 39],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '40', 'VALUE' => '福岡県', 'VALUE_KANA' => 'フクオカケン', 'VALUE_ENGLISH' => 'Fukuoka', 'DISP_ORDER' => 40],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '41', 'VALUE' => '佐賀県', 'VALUE_KANA' => 'サガケン', 'VALUE_ENGLISH' => 'Saga', 'DISP_ORDER' => 41],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '42', 'VALUE' => '長崎県', 'VALUE_KANA' => 'ナガサキケン', 'VALUE_ENGLISH' => 'Nagasaki', 'DISP_ORDER' => 42],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '43', 'VALUE' => '熊本県', 'VALUE_KANA' => 'クマモトケン', 'VALUE_ENGLISH' => 'Kumamoto', 'DISP_ORDER' => 43],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '44', 'VALUE' => '大分県', 'VALUE_KANA' => 'オオイタケン', 'VALUE_ENGLISH' => 'Oita', 'DISP_ORDER' => 44],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '45', 'VALUE' => '宮崎県', 'VALUE_KANA' => 'ミヤザキケン', 'VALUE_ENGLISH' => 'Miyazaki', 'DISP_ORDER' => 45],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '46', 'VALUE' => '鹿児島県', 'VALUE_KANA' => 'カゴシマケン', 'VALUE_ENGLISH' => 'Kagoshima', 'DISP_ORDER' => 46],
            ['TYPE_CODE' => 'PREFECTURE', 'KEY' => '47', 'VALUE' => '沖縄県', 'VALUE_KANA' => 'オキナワケン', 'VALUE_ENGLISH' => 'Okinawa', 'DISP_ORDER' => 47],

            // ロール
            ['TYPE_CODE' => 'ROLE_ID', 'KEY' => 'MA01', 'VALUE' => 'マルホ管理者', 'VALUE_KANA' => 'マルホカンリシャ', 'VALUE_ENGLISH' => 'MaruhoAdmin', 'DISP_ORDER' => 1],
            ['TYPE_CODE' => 'ROLE_ID', 'KEY' => 'NA01', 'VALUE' => '中島弘文堂管理者', 'VALUE_KANA' => 'ナカジマコウブンドウカンリシャ', 'VALUE_ENGLISH' => 'NakajimaAdmin', 'DISP_ORDER' => 2],
            ['TYPE_CODE' => 'ROLE_ID', 'KEY' => 'MU01', 'VALUE' => 'マルホ利用者', 'VALUE_KANA' => 'マルホリヨウシャ', 'VALUE_ENGLISH' => 'MaruhoUser', 'DISP_ORDER' => 3],

            // 単位（UNIT_TYPE）
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '00', 'VALUE' => '個', 'VALUE_KANA' => 'コ', 'VALUE_ENGLISH' => 'Ko', 'DISP_ORDER' => 1],
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '01', 'VALUE' => '枚', 'VALUE_KANA' => 'マイ', 'VALUE_ENGLISH' => 'Mai', 'DISP_ORDER' => 2],
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '02', 'VALUE' => '冊', 'VALUE_KANA' => 'サツ', 'VALUE_ENGLISH' => 'Satsu', 'DISP_ORDER' => 3],
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '03', 'VALUE' => '本', 'VALUE_KANA' => 'ホン', 'VALUE_ENGLISH' => 'Hon', 'DISP_ORDER' => 4],
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '04', 'VALUE' => '束', 'VALUE_KANA' => 'ソク', 'VALUE_ENGLISH' => 'Soku', 'DISP_ORDER' => 5],
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '05', 'VALUE' => 'セット', 'VALUE_KANA' => 'セット', 'VALUE_ENGLISH' => 'Setto', 'DISP_ORDER' => 6],
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '06', 'VALUE' => '箱', 'VALUE_KANA' => 'ハコ', 'VALUE_ENGLISH' => 'Hako', 'DISP_ORDER' => 7],
            ['TYPE_CODE' => 'UNIT_TYPE', 'KEY' => '07', 'VALUE' => '袋', 'VALUE_KANA' => 'フクロ', 'VALUE_ENGLISH' => 'Fukuro', 'DISP_ORDER' => 8],
        ];

        foreach ($records as $row) {
            $row['CREATE_DT'] = $now;
            $row['CREATE_APP'] = 'seeder';
            $row['CREATE_USER'] = 'system';
            $row['UPDATE_DT'] = $now;
            $row['UPDATE_APP'] = 'seeder';
            $row['UPDATE_USER'] = 'system';

            DB::table('M_GENERAL_TYPE')->updateOrInsert(
                ['TYPE_CODE' => $row['TYPE_CODE'], 'KEY' => $row['KEY']],
                $row
            );
        }
    }
}
