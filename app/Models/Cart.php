<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'CART';
    public $timestamps = false;

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'USER_ID',
        'TOOL_CODE',
        'QUANTITY',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];

    public function tool()
    {
    return $this->belongsTo(Tool::class, 'TOOL_CODE', 'TOOL_CODE');
    }
}
