<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'city';
    protected $fillable = [
        'name',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_city', 'city_id', 'product_id');
    }

}
