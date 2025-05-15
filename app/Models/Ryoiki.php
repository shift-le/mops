<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ryoiki extends Model
{
    protected $table = 'ryoiki';
    protected $primaryKey = 'RYOIKI_CODE';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function hinmeis()
    {
        return $this->hasMany(Hinmei::class, 'RYOIKI_CODE', 'RYOIKI_CODE');
    }
}
