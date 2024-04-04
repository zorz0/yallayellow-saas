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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_name')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('coupon_type')->default('percentage')->comment('percentage / flat');
            $table->text('applied_product')->nullable();
            $table->text('exclude_product')->nullable();
            $table->text('applied_categories')->nullable();
            $table->text('exclude_categories')->nullable();
            $table->integer('minimum_spend')->nullable();
            $table->integer('maximum_spend')->nullable();
            $table->integer('coupon_limit_user')->nullable();
            $table->integer('coupon_limit_x_item')->nullable();
            $table->integer('coupon_limit')->default('0');
            $table->date('coupon_expiry_date')->nullable();
            $table->float('discount_amount')->default('0');
            $table->integer('sale_items')->default(0);
            $table->integer('free_shipping_coupon')->default(0);
            $table->integer('status')->default(1)->comment('0 => Inactive, 1 => Active ');
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
        Schema::dropIfExists('coupons');
    }
};
