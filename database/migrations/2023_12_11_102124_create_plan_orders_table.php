<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->unique();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('card_number', 10)->nullable();
            $table->string('card_exp_month', 10)->nullable();
            $table->string('card_exp_year', 10)->nullable();
            $table->string('plan_name', 100);
            $table->integer('plan_id');
            $table->float('price')->nullable();

            $table->longText('coupon')->nullable();
            $table->longText('coupon_json')->nullable();
            $table->string('discount_price')->nullable();
            
            $table->string('price_currency', 10);
            $table->string('txn_id', 100);
            $table->string('payment_status', 100);
            $table->string('payment_type')->nullable();
            $table->string('receipt')->nullable();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->integer('store_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_orders');
    }
};
