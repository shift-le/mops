<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorite';
    public $timestamps = true;

    protected $fillable = [
        'USER_ID',
        'TOOL_CODE',
    ];
}
