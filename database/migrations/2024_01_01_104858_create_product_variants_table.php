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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('variant');
            $table->string('sku');
            $table->double('price',10,2);
            $table->integer('stock')->default(0);
            $table->double('variation_price',10,2)->nullable();
            $table->integer('weight')->nullable();
            $table->string('stock_order_status')->nullable();
            $table->integer('low_stock_threshold')->default(0);
            $table->string('downloadable_product')->nullable();
            $table->string('variation_option')->nullable();
            $table->text('description')->nullable();
            $table->string('stock_status')->nullable();
            $table->string('shipping')->nullable();
            $table->string('theme_id');
            $table->integer('store_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
