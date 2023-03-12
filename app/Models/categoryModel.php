<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoryModel extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable =['name', 'icon', 'description','bgColor'];


    public function products() {
        return $this->hasMany(productModel::class, 'categories_id','id');
    }
}
