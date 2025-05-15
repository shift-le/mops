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
        'TOOL_CODE',
        'TOOL_NAME',
        'TOOL_THUM_FILE',
        'TOOL_PDF_FILE',
        'TOOL_STATUS',
        'HINMEI',
        'RYOIKI',
        'DEL_FLG',
        'CREATE_DT',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_USER',
        // 他必要なカラム
    ];

    protected $casts = [
    'DISPLAY_START_DATE' => 'datetime',
    ];

    // 品名とのリレーション（Tool belongsTo Hinmei）
    public function hinmei()
    {
        return $this->belongsTo(Hinmei::class, 'HINMEI', 'HINMEI_CODE');
    }

    // 領域とのリレーション（Tool belongsTo Ryoiki）
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
