<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'PASSWORD_RESET_TOKENS';
    public $timestamps = true;
    public $incrementing = false;
    public $primaryKey = null;

    protected $fillable = [
        'EMAIL', 'TOKEN', 'created_at', 'CREATE_DT', 'CREATE_APP', 'CREATE_USER',
        'UPDATE_DT', 'UPDATE_APP', 'UPDATE_USER'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->CREATE_DT = $model->created_at ?? now();
        });

        static::updating(function ($model) {
            $model->UPDATE_DT = $model->updated_at ?? now();
        });
    }
}
