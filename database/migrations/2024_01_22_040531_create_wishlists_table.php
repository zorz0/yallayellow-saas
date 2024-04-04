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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->default('0');
            $table->unsignedBigInteger('product_id')->nullable()->default('0');
            $table->integer('variant_id')->default('0');
            $table->integer('status')->default('1')->comment('0 => inactive, 1 => active');
            $table->string('theme_id')->nullable();
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
        Schema::dropIfExists('wishlists');
    }
};
