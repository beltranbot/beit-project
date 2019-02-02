<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    protected $table = 'customer_product';
    protected $fillable = [
        'customer_id',
        'product_id'
    ];
    public $timestamps = false;

    function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
