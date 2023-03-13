<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class product extends Model
{



    use HasFactory, Searchable;

    protected $fillable = [
        'name', 'image', 'description', 'price', 'weight', 'product_type', 'product_calori', 'categories_id', 'discount_id'
    ];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) $value;
    }

    // Searchable as
    public function searchableAs()
    {
        return 'products_index';
    }

    //toSearchableArray
    public function toSearchableArray()
    {

        return [
            'name' => $this->name,
        ];
    }




    public function category()
    {
        return $this->belongsTo(categoryModel::class, 'categories_id');
    }


    public function locations()
    {
        return $this->belongsToMany(location::class, 'product_locations', 'product_id', 'location_id');
    }

    public function product_locations()
    {
        return $this->hasMany(product_location::class);
    }

    public function discount()
    {
        return $this->belongsTo(discount::class, 'discount_id');
    }

    public function groupProducts()
    {
        return $this->belongsToMany(groupProduct::class, 'group_products', 'product_id', 'product_group_id');
    }
}
