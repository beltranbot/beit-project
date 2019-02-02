<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $guard = ['customer_id'];
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'customer_product', 'customer_id', 'product_id');
    }
}
