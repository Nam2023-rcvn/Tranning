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
        Schema::create('order_details', function (Blueprint $table) {
            $table->integer('order_id')->unique();
            $table->integer('detail_line');
            $table->string('product_id', 50);
            $table->integer('price_buy');
            $table->integer('quantity');
            $table->string('shop_id', 50);
            $table->integer('receiver_id');
            $table->nullableTimestamps();

            $table->primary(['order_id','detail_line']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
