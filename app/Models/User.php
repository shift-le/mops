<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'USERS';

    protected $primaryKey = 'USER_ID';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    // 一括代入を許可するカラム
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

    // 非表示にする属性
    protected $hidden = [
        'PASSWORD',
        'remember_token',
    ];

    // キャスト設定（オプション）
    protected function casts(): array
    {
        return [
            'CREATE_DT' => 'datetime',
            'UPDATE_DT' => 'datetime',
            'PASSWORD' => 'hashed',
        ];
    }
}
