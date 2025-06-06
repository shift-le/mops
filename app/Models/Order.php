<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'ORDER';
    protected $primaryKey = 'ORDER_CODE';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    public function details()
    {
        return $this->hasMany(OrderMeisai::class, 'ORDER_CODE', 'ORDER_CODE');
    }

}
