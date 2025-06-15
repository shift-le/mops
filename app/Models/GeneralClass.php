<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralClass extends Model
{
    protected $table = 'M_GENERAL_TYPE';
    protected $primaryKey = 'KEY';
    public $incrementing = false;
    public $timestamps = false;
}
