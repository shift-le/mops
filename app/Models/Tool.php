<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $table = 'TOOL';

    // 主キーが自動増分（id）でない場合は設定
    protected $primaryKey = 'TOOL_CODE';
    public $incrementing = false;

    // Laravelの自動タイムスタンプ（created_at/updated_at）は使わない場合
    public $timestamps = false;

    protected $fillable = [
        'TOOL_STATUS',
        'TOOL_NAME',
        'TOOL_NAME_KANA',
        'TOOL_CODE',
        'RYOIKI',
        'HINMEI',
        'TOOL_TYPE1',
        'TOOL_TYPE2',
        'TOOL_SETUMEI',
        'REMARKS',
        'TANKA',
        'DISPLAY_START_DATE',
        'DISPLAY_END_DATE',
        'TOOL_SETSUMEI4',
        'MST_FLG',
        'KANRI_LIMIT_DATE',
        'SOSHIKI1',
        'SOSHIKI2',
        'TOOL_MANAGER1_ID',
        'TOOL_MANAGER1_NAME',
        'TOOL_MANAGER2_ID',
        'TOOL_MANAGER2_NAME',
        'TOOL_MANAGER3_ID',
        'TOOL_MANAGER3_NAME',
        'TOOL_MANAGER4_ID',
        'TOOL_MANAGER4_NAME',
        'TOOL_MANAGER5_ID',
        'TOOL_MANAGER5_NAME',
        'TOOL_MANAGER6_ID',
        'TOOL_MANAGER6_NAME',
        'TOOL_MANAGER7_ID',
        'TOOL_MANAGER7_NAME',
        'TOOL_MANAGER8_ID',
        'TOOL_MANAGER8_NAME',
        'TOOL_MANAGER9_ID',
        'TOOL_MANAGER9_NAME',
        'TOOL_MANAGER10_ID',
        'TOOL_MANAGER10_NAME',
        'ADMIN_MEMO',
        'TOOL_PDF_FILE',
        'TOOL_THUM_FILE',
        'DEL_FLG',
        'CREATE_DT',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_USER',
    ];

    protected $casts = [
    'DISPLAY_START_DATE' => 'datetime',
    ];

    // Tool∊hinmei
    public function hinmei()
    {
        return $this->belongsTo(Hinmei::class, 'HINMEI', 'HINMEI_CODE');
    }

    // Tool∊ryoiki
    public function ryoiki()
    {
        return $this->belongsTo(Ryoiki::class, 'RYOIKI', 'RYOIKI_CODE');
    }

    public function getUnitNameAttribute()
{
    $labels = [
        '00' => '個',
        '01' => '枚',
        '02' => '冊',
        '03' => '本',
        '04' => '束',
        '05' => 'セット',
        '06' => '箱',
        '07' => '袋',
    ];
    return $labels[$this->UNIT_TYPE] ?? '';
}

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'TOOL_CODE', 'TOOL_CODE');
    }

}
