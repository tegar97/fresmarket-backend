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
        Schema::create('voucher', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('voucher_description');
            $table->double('discount_percetange');
            $table->integer('min_order');
            $table->double('max_discount');
            $table->integer('max_use');
            $table->string('voucher_type');
            $table->integer('use_count');
            $table->dateTime('expired_at');


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
        Schema::dropIfExists('voucher');
    }
};
