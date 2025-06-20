<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'FAQ'; // テーブル名を明示（小文字だと複数形解釈される可能性あり）

    public $timestamps = false; // 自動の created_at/updated_at は使わない

    protected $fillable = [
        'FAQ_CODE',
        'FAQ_TITLE',
        'FAQ_QUESTION',
        'FAQ_ANSWER',
        'DISP_ORDER',
        'HYOJI_FLG',
        'DEL_FLG',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];

    protected $casts = [
        'CREATE_DT' => 'datetime',
        'UPDATE_DT' => 'datetime',
        'HYOJI_FLG' => 'boolean',
        'DEL_FLG' => 'boolean',
        'DISP_ORDER' => 'integer',
    ];
}
