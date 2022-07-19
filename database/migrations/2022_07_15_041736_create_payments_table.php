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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('midtrans_order_id');
            $table->integer('users_id');
            $table->integer('amount');
            $table->longText('payment_url');
            $table->integer('expire_time_unix');
            $table->string('expire_time_str');
            $table->string('service_name')->nullable();
            $table->string('service_code')->nullable();
            $table->integer('payment_status');
            $table->string('payment_status_str')->nullable();
            $table->string('payment_code')->nullable();
            $table->string('payment_key')->nullable();
            $table->string('snap_url');
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
        Schema::dropIfExists('payments');
    }
};
