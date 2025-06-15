<?php

namespace Database\Seeders;

use App\Models\Thuzaiin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThuzaiinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Thuzaiin::updateOrInsert(
            ['USER_ID' => 'user001'],
            [
                'POST_CODE' => '5006789',
                'PREFECTURE' => '27',
                'ADDRESS1' => '大阪市駐在区1-1',
                'ADDRESS2' => '101号室',
                'ADDRESS3' => '駐在ビル',
                'TEL' => '1234567890',
                'DELI_NAME' => 'サンプル　太郎',
                'DEL_FLG' => '0',
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
