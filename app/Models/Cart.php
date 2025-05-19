<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    public $timestamps = true;

    protected $fillable = [
        'USER_ID',
        'TOOL_CODE',
        'QUANTITY',
    ];

    public function tool()
    {
    return $this->belongsTo(Tool::class, 'TOOL_CODE', 'TOOL_CODE');
    }
}
