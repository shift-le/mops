<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralClass extends Model
{
    protected $table = 'M_GENERAL_TYPE';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'TYPE_CODE',
        'KEY',
        'VALUE',
        'VALUE_KANA',
        'VALUE_ENGLISH',
        'REMARK',
        'DISP_ORDER',
        'PRELIMINARY_ITEM1',
        'PRELIMINARY_ITEM2',
        'PRELIMINARY_ITEM3',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];
}
