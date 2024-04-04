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
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('refund_status');
            $table->longText('refund_reason');
            $table->string('custom_refund_reason')->nullable();
            $table->longText('attachments')->nullable();
            $table->string('product_refund_id')->nullable();
            $table->string('product_refund_price')->default(0);
            $table->float('refund_amount')->default(0);
            $table->string('store_id');
            $table->string('theme_id');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_refunds');
    }
};
