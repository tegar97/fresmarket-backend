<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class store extends Model
{
    use HasFactory;
    protected $table = 'store';
    protected $fillable = [
        'name', 'location_id', 'address', 'latitude', 'longitude'


    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
