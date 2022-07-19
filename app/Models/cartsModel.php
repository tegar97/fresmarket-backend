<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cartsModel extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $fillable = ['total'];
}
