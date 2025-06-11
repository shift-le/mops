<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thuzaiin extends Model
{
    protected $table = 'THUZAIIN';
    protected $primaryKey = 'USER_ID';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'USER_ID',
        'POST_CODE1',
        'POST_CODE2',
        'PREF_ID',
        'ADDRESS1',
        'ADDRESS2',
        'ADDRESS3',
        'TEL',
        'DEL_FLG',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];

    public function prefecture()
    {
        return $this->hasOne(GeneralClass::class, 'PREFECTURE_KEY', 'PREF_ID');
    }
}
