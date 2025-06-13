<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soshiki1 extends Model
{
    protected $table = 'M_SOSHIKI1';
    protected $primaryKey = 'SHITEN_BU_CODE';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    public function prefecture()
{
    return $this->hasOne(GeneralClass::class, 'PREFECTURE_KEY', 'PREF_ID');
}
}
