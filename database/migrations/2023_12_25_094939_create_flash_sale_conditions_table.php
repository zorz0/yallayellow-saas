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
        Schema::create('flash_sale_conditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flashsale_id')->nullable();
            $table->longText('condition')->nullable()->comment('0 => Shop, 1 => Product, 2 => Category, 3 => Product price');
            $table->string('theme_id')->nullable();
            $table->integer('store_id');
            $table->timestamps();

            $table->foreign('flashsale_id')->references('id')->on('flash_sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale_conditions');
    }
};
