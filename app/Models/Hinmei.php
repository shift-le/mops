<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hinmei extends Model
{
    protected $table = 'M_HINMEI';
    protected $primaryKey = 'HINMEI_CODE';
    public $incrementing = false;
    public $timestamps = false;

public function tools()
{
    return $this->hasMany(Tool::class, 'RYOIKI', 'HINMEI_CODE');
}

public function ryoikis()
{
    return $this->hasManyThrough(
        Ryoiki::class,
        Tool::class,
        'RYOIKI',           // Tool の外部キー → Hinmei.HINMEI_CODE
        'RYOIKI_CODE',      // Ryoiki の主キー
        'HINMEI_CODE',      // Hinmei の主キー
        'HINMEI'            // Tool の HINMEI（Ryoiki と一致）
    )->distinct('RYOIKI.RYOIKI_CODE');
}

public function getRyoikiCountAttribute()
{
    return $this->ryoikis()->distinct()->count('RYOIKI_CODE');
}

}
