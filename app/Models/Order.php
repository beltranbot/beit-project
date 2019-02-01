<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'order_id';
    protected $guard = ['order_id'];

    function order_details()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }
}
