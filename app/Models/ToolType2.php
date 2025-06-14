<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolType2 extends Model
{
    protected $table = 'M_TOOL_TYPE2';
    protected $primaryKey = 'TOOL_TYPE2';
    public $timestamps = false;

    protected $fillable = [
        'TOOL_TYPE2',
        'TOOL_TYPE2_NAME',
        'DISP_ORDER',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];
}
