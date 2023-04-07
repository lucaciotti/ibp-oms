<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MachineJob
 *
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Package|null $package
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|MachineJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MachineJob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MachineJob query()
 * @mixin \Eloquent
 */
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
