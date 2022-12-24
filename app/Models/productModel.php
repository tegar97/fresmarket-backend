<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable =['categories_id','name','image', 'description','price','weight','product_type','product_exp','product_calori'];


    public function categories()
    {
        return $this->belongsTo(categoryModel::class, 'categories_id', 'id');
    }
    public function cities()
    {
        return $this->belongsToMany(City::class, 'product_city', 'product_id', 'city_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'tag_product', 'product_id', 'tag_id');
    }
}
