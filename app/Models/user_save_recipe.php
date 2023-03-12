<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_save_recipe extends Model
{
    use HasFactory;
    protected $table = 'user_save_recipes';
    protected $fillable =['user_id', 'recipe_name', 'recipe_video_id','recipe_description','recipe_ingredients','recipe_steps'];
}
