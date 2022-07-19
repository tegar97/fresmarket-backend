<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderModel extends Model
{
    protected $table = "order";
    protected $fillable = ["amount", "shipping_amount","payments_id"];
    public function orderItem()
    {
        return $this->hasMany(orderItemModel::class, 'order_id', 'id');
    }
    use HasFactory;
}
