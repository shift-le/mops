<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'USERS';
    protected $primaryKey = 'USER_ID';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'USER_ID',
        'SHAIN_ID',
        'NAME',
        'NAME_KANA',
        'PASSWORD',
        'EMAIL',
        'MOBILE_TEL',
        'MOBILE_EMAIL',
        'SHITEN_BU_CODE',
        'EIGYOSHO_GROUP_CODE',
        'ROLE_ID',
        'DEL_FLG',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];

    protected $hidden = [
        'PASSWORD',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'CREATE_DT' => 'datetime',
            'UPDATE_DT' => 'datetime',
            'PASSWORD' => 'hashed',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'USER_ID';
    }

    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }

    public function soshiki1()
    {
        return $this->belongsTo(Soshiki1::class, 'SHITEN_BU_CODE', 'SHITEN_BU_CODE');
    }

    public function soshiki2()
    {
        return $this->belongsTo(Soshiki2::class, 'EIGYOSHO_GROUP_CODE', 'EIGYOSHO_GROUP_CODE');
    }
}
