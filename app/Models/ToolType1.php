<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolType1 extends Model
{
    // テーブル名
    protected $table = 'M_TOOL_TYPE1';

    // 主キーがあるなら指定（なければデフォルトの 'id' が使われるので注意）
    protected $primaryKey = 'TOOL_TYPE1';

    // タイムスタンプ自動更新無効（カラム名が標準の created_at / updated_at じゃないため）
    public $timestamps = false;

    // 代入を許可するカラム
    protected $fillable = [
        'TOOL_TYPE1',
        'TOOL_TYPE1_NAME',
        'DISP_ORDER',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];
}
