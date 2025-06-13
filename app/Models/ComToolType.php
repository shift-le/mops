<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MComToolType extends Model
{
    protected $table = 'M_COM_TOOL_TYPE';
    protected $primaryKey = 'COM_TOOL_TYPE';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'COM_TOOL_TYPE',
        'COM_TOOL_TYPE_NAME',
        'DISP_ORDER',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];
}