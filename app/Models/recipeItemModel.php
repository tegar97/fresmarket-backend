<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recipeItemModel extends Model
{
    use HasFactory;
    protected $table = 'recipe_item';
    protected $fillable = ['qty','products_id', 'recipe_id'];

    public function product() {
        return $this->hasOne(productModel::class,'id','products_id');
    }

}
