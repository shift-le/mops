<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MToolType2 extends Model
{
    protected $table = 'M_TOOL_TYPE2';
    protected $primaryKey = ['TOOL_TYPE1', 'TOOL_TYPE2'];
    public $incrementing = false;
    public $timestamps = false;
}
