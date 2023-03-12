<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_group extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',

    ];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'group_products');
    }

    public function groupProducts()
    {
        return $this->hasMany(group_product::class);
    }
}
