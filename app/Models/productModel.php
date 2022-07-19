<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable =['categories_id','name','image', 'description','price','weight','product_type','product_exp','product_calori'];

    
}
