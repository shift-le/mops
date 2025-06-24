<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ryoiki extends Model
{
    protected $table = 'M_RYOIKI';
    protected $primaryKey = 'RYOIKI_CODE';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function hinmei()
    {
        return $this->belongsTo(Hinmei::class, 'HINMEI_CODE', 'HINMEI_CODE');
    }

public function tools()
{
    return $this->hasMany(Tool::class, 'HINMEI', 'RYOIKI_CODE');
}

}
