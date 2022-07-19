<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cartItemsModel extends Model
{
    use HasFactory;
    protected $table = 'cart_items';
    protected $fillable = ['carts_id', 'products_id','qty', 'total'];
}
