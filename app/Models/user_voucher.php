<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_voucher extends Model
{
    use HasFactory;
    protected $table = 'user_voucher';
    protected $fillable = ['users_id', 'voucher_id', 'is_used'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }




}
