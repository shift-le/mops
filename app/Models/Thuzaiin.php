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

    protected $fillable =
    [
        'DELI_NAME'
    ];

    public function prefecture()
    {
        return $this->hasOne(GeneralClass::class, 'PREFECTURE_KEY', 'PREF_ID');
    }
}
