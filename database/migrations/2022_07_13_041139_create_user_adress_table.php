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
        Schema::create('user_adress', function (Blueprint $table) {
            $table->id();
            $table->integer('users_id');
            $table->text("fullAddress");
            $table->string("province");
            $table->string("city");
            $table->string("districts");
            $table->string("phoneNumber");
            $table->boolean("isMainAddress");
        

        
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
        Schema::dropIfExists('user_adress');
    }
};
