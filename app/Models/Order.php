<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'ORDER';
    protected $primaryKey = 'ORDER_CODE';
    public $incrementing = false;
<<<<<<< HEAD
    public $timestamps = false;
    protected $keyType = 'string';
    public function details()
    {
        return $this->hasMany(OrderMeisai::class, 'ORDER_CODE', 'ORDER_CODE');
    }

=======
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ORDER_CODE', 'ORDER_STATUS', 'ORDER_TOOLID', 'ORDER_NAME', 'CREATE_DT', 'AMOUNT'
    ];
>>>>>>> USER_ord_history
}
