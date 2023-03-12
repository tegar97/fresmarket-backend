<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class discount extends Model
{
    use HasFactory;
    protected $fillable = [
      'discount_percetage'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
