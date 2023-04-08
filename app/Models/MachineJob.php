<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineJob extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer(){
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function cart(){
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }

    public function package(){
        return $this->hasOne(Package::class, 'id', 'package_id');
    }
}
