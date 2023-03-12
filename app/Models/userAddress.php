<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userAddress extends Model
{
    use HasFactory;
    protected $table = "user_adress";
    protected $fillable = ['users_id', 'label', 'fullAddress','province','city','districts','phoneNumber','isMainAddress','street', 'latitude', 'longitude' , 'recipient', 'message_courier'];
}
