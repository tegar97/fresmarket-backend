<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recipeModel extends Model
{
    use HasFactory;
    protected $table ='recipe';
    protected $fillable = ['title','description','image','calori','level', 'estimateTime', 'step'];


    public function recipeItem() {
        return $this->hasMany(recipeItemModel::class, 'recipe_id','id');
    }

    // products
    public function products() {
        return $this->belongsToMany(product::class, 'recipe_item', 'recipe_id', 'products_id');
    }

}
