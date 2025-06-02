<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hinmei extends Model
{
    protected $table = 'HINMEI';
    protected $primaryKey = 'HINMEI_CODE';
    public $incrementing = false;
    public $timestamps = false;

    public function ryoiki()
    {
        return $this->belongsTo(Ryoiki::class, 'RYOIKI_CODE', 'RYOIKI_CODE');
    }

    public function tools()
    {
        return $this->hasMany(Tool::class, 'HINMEI', 'HINMEI_CODE');
    }

    public function getToolCountAttribute()
    {
        return $this->tools()->count();
    }

}
