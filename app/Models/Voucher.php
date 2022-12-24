<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'voucher';
    protected $fillable = ['code', 'discount_percetange', 'expired_at','max_discount', 'min_order', 'max_use', 'use_count','voucher_type', 'voucher_description'];

    public function user_voucher()
    {
        return $this->hasMany(user_voucher::class);
    }
}
