<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentModel extends Model
{
    use HasFactory;
    protected $table = "payments";
    protected $fillable = ['snap_url', 'users_id', 'amount', 'expire_time_unix', 'expire_time_str', 'service_name', 'service_code', 'payment_status', 'payment_code', 'payment_key', 'payment_url', 'midtrans_order_id', 'delivery_type'];

}
