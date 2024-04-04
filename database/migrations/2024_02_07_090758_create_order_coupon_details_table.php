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
        Schema::create('order_coupon_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('product_order_id')->nullable()->comment('get from order table');
            $table->integer('coupon_id')->default(0);
            $table->string('coupon_name')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('coupon_discount_type')->nullable();
            $table->float('coupon_discount_number')->default(0);
            $table->float('coupon_discount_amount')->default(0);
            $table->float('coupon_final_amount')->default(0);
            $table->string('theme_id')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_coupon_details');
    }
};
