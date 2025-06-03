<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralClass extends Model
{
    protected $table = 'GENERAL_CLASS';
    protected $primaryKey = 'PREFECTURE_KEY';
    public $incrementing = false;
    public $timestamps = false;
}
