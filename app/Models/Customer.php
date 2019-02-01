<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $guard = ['customer_id'];

    public function customer_products()
    {
        return $this->hasMany('App\Models\CustomerProduct', 'customer_id');
    }
}
