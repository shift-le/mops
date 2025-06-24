<?php

// app/Models/KeijibanTemp.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeijibanTemp extends Model
{
    protected $table = 'KEIJIBAN_TEMP';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'KEIJIBAN_CODE',
        'FILE_NO',
        'FILE_NAME',
        'CREATE_DT',
        'CREATE_APP',
        'CREATE_USER',
        'UPDATE_DT',
        'UPDATE_APP',
        'UPDATE_USER',
    ];
}
