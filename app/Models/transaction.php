<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    protected $fillable = ['invoice', 'amount', 'shipping_amount', 'transction_item_id', 'users_id', 'payment_id', 'status', 'status_str', 'delivery_type'];


    public function transactionItem() {
        return $this->hasMany(transactionItem::class, 'transaction_id','id');
    }
}
