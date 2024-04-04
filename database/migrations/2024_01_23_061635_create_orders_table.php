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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('product_order_id')->nullable();
            $table->dateTime('order_date')->nullable();
            $table->integer('customer_id')->default(0);
            $table->integer('is_guest')->default(0)->comment('0=>no/1=>yes');
            $table->text('product_json')->nullable();
            $table->string('product_id')->default(0);
            $table->float('product_price')->nullable()->default(0);
            $table->float('coupon_price')->nullable()->default(0);
            $table->float('delivery_price')->nullable()->default(0);
            $table->float('tax_price')->nullable()->default(0);
            $table->float('final_price')->nullable()->default(0);
            $table->float('return_price')->nullable()->default(0);
            $table->text('payment_comment')->nullable();
            $table->string('payment_type')->nullable()->comment('cod/bank_transfer/paypal');
            $table->string('payment_status')->nullable();
            $table->integer('delivery_id')->default(0);
            $table->text('delivery_comment')->nullable();
            $table->integer('delivered_status')->default(0)->comment('0=>pending/1=>diliver/2=>cancel/3=>return');
            $table->date('delivery_date')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->date('picked_date')->nullable();
            $table->date('shipped_date')->nullable();
            $table->integer('return_status')->default(0)->comment('0 => none, 1 => request, 2=>approve, 3 => cancel');
            $table->date('return_date')->nullable();
            $table->date('cancel_date')->nullable();
            $table->float('reward_points', 8, 2)->default(0);
            $table->string('additional_note')->nullable();
            $table->string('theme_id')->nullable();
            $table->integer('store_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
