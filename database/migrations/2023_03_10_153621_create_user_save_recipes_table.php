<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_save_recipes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('recipe_name');
            $table->string('recipe_video_id');
            $table->string('recipe_description');
            $table->string('recipe_ingredients');
            $table->text('recipe_steps');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_save_recipes');
    }
};
