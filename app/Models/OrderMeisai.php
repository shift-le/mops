<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMeisai extends Model
{
    protected $table = 'ORDER_MEISAI';
    protected $fillable = [
        'TOOL_CODE',
        'TOOLID',
        'ORDER_CODE',
        'ORDER_NO',
        'ORDER_DATE',
        'ORDER_USER_ID',
        'ORDER_USER_NAME',
        'ORDER_USER_NAME_KANA',
        'ORDER_USER_EMAIL',
        'ORDER_USER_TEL',
        'ORDER_USER_MOBILE_TEL',
        'ORDER_USER_POSTAL_CODE',
        'ORDER_USER_ADDRESS1',
        'ORDER_USER_ADDRESS2',
        'DELIVERY_DATE',
        'DELIVERY_TIME',
        'DELIVERY_ADDRESS1',
        'DELIVERY_ADDRESS2',
        'DELIVERY_POSTAL_CODE',
        'DELIVERY_TEL',
        'DELIVERY_MOBILE_TEL',
        'DELIVERY_NAME',
        'DELIVERY_NAME_KANA',
    ];
    protected $primaryKey = ['TOOL_CODE', 'TOOLID'];
    public $incrementing = false;
    public $timestamps = false;

}

