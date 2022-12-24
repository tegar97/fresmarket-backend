<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagProduct extends Model
{
    use HasFactory;
    protected $table = 'tags_product';
    protected $fillable = [
        'product_id',
        'tags_id',
    ];
    public function products()
    {
        return $this->belongsToMany(productModel::class, 'tag_product', 'tags_id', 'product_id');
    }

    public function tags(){
        return $this->belongsToMany(Tags::class, 'tag_product', 'product_id', 'tags_id');
    }


}
