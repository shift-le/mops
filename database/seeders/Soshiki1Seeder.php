<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Soshiki1;

class Soshiki1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Soshiki1::updateOrInsert(
            ['SHITEN_BU_CODE' => '101'],
            [
                'COMPANY_TYPE' => '2',
                'SOSHIKI1_NAME' => '営業サンプル部',
                'SOSHIKI1_SHORT_NAME' => '営業サンプル部',
                'POST_CODE' => '1001234',
                'PREFECTURE' => '27',
                'ADDRESS1' => 'テスト大阪市北区中津1丁目1-1',
                'ADDRESS2' => 'テストセンタービル',
                'ADDRESS3' => 'テスト1号館',
                'TEL' => '01-1234-5678',
                'FAX' => '01-1234-5679',
                'ACTIVE_FLG' => '1',
                'DEL_FLG' => '0',
                'DEL_DT' => null,
                'CREATE_DT' => null,
                'CREATE_APP' => null,
                'CREATE_USER' => null,
                'UPDATE_DT' => null,
                'UPDATE_APP' => null,
                'UPDATE_USER' => null,
            ]
        );
        Soshiki1::updateOrInsert(
            ['SHITEN_BU_CODE' => '102'],
            [
                'COMPANY_TYPE' => '2',
                'SOSHIKI1_NAME' => '営業サンプル部2',
                'SOSHIKI1_SHORT_NAME' => '営業サンプル部2',
                'POST_CODE' => '1012345',
                'PREFECTURE' => '27',
                'ADDRESS1' => 'テスト大阪市北区中津1丁目1-1',
                'ADDRESS2' => 'テストセンタービル',
                'ADDRESS3' => 'テスト2号館',
                'TEL' => '01-1234-5678',
                'FAX' => '01-1234-5679',
                'ACTIVE_FLG' => '1',
                'DEL_FLG' => '0',
                'DEL_DT' => null,
                'CREATE_DT' => null,
                'CREATE_APP' => null,
                'CREATE_USER' => null,
                'UPDATE_DT' => null,
                'UPDATE_APP' => null,
                'UPDATE_USER' => null,
            ]
        );
    }
}
