<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soshiki2 extends Model
{
    protected $table = 'M_SOSHIKI2';
    protected $primaryKey = 'EIGYOSHO_GROUP_CODE';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    public function prefecture()
    {
        return $this->hasOne(GeneralClass::class, 'PREFECTURE_KEY', 'PREF_ID');
    }
}
