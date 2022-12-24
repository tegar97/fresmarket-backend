<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCity extends Model
{
    use HasFactory;
    protected $table = 'product_city';
    protected $fillable = [
        'product_id',
        'city_id',
    ];
    public function products()
    {
        return $this->belongsToMany(productModel::class, 'product_city', 'city_id', 'product_id');
    }
    
    public function cities()
    {
        return $this->belongsToMany(City::class, 'product_city', 'product_id', 'city_id');
    }
}
