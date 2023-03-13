<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductViewHistory extends Model
{
    use HasFactory;
    protected $table = 'product_view_history';
    protected $fillable = [
        'user_id',
        'product_id'
    ];

    //relation with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relation with product
    public function product()
    {
        return $this->belongsTo(product::class);
    }


}
