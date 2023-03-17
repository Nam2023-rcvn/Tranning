<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('order_id')->autoIncrement();
            $table->string('order_shop', 40);
            $table->integer('customer_id');
            $table->integer('total_price');
            $table->tinyInteger('payment_method');
            $table->integer('ship_charge');
            $table->integer('tax');
            $table->dateTime('order_date');
            $table->dateTime('shipment_date');
            $table->dateTime('cancel_date');
            $table->boolean('order_status');
            $table->string('note_customer')->nullable();
            $table->string('error_code_api', 20)->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
